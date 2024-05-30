<?php

/**
 * Freight
 *
 * @author  Jay Trees <freight@grandels.email>
 * @link    https://github.com/grandeljay/modified-freight
 * @package GrandelJayFreight
 */

namespace Grandeljay\Freight;

class Quote
{
    private array $calculations = [];
    private array $pallets      = [];
    private array $methods      = [];

    public function __construct(string $module)
    {
        $this->pallets = $this->getPallets();
        $this->methods = $this->getShippingMethods();
    }

    private function getShippingWeight(): float
    {
        $shipping_weight = 0;

        foreach ($this->pallets as $pallet) {
            $shipping_weight += $pallet->getWeight();
        }

        return $shipping_weight;
    }

    private function getPallets(): array
    {
        global $order;

        $products          = $order->products;
        $pallets           = [];
        $pallet_max_weight = (float) constant(\grandeljayfreight::NAME . '_WEIGHT_PER_PALLET');

        foreach ($products as $product) {
            for ($i = 1; $i <= $product['quantity']; $i++) {
                $product_weight = (float) $product['weight'];

                /** Find a pallet empty enough to fit product */
                foreach ($pallets as &$pallet) {
                    $pallet_weight          = $pallet->getWeight();
                    $pallet_can_fit_product = $pallet_weight + $product_weight < $pallet_max_weight;

                    if ($pallet_can_fit_product) {
                        $pallet->addProduct($product);

                        continue 2;
                    }
                }

                /** Break the reference binding so $pallet can be assigned a new value */
                unset($pallet);

                /** Add product to a new box */
                $pallet = new Pallet();
                $pallet->addProduct($product);

                /** Add box to list */
                $pallets[] = $pallet;
            }
        }

        return $pallets;
    }

    private function getShippingMethods(): array
    {
        $methods        = [];
        $method_freight = $this->getShippingMethodFreight();

        if ($method_freight['cost'] > 0) {
            $methods[] = $method_freight;
        }

        return $methods;
    }

    private function getShippingMethodFreight(): array
    {
        global $order;

        $shipping_method_freight = [
            'id'    => 'freight',
            'title' => sprintf(
                '%s (%s)' . '<!-- BREAK -->',
                constant(\grandeljayfreight::NAME . '_LONG_DESCRIPTION'),
                $this->getNameBoxWeight()
            ),
            'cost'  => 0,
        ];

        $shipping_weight = $this->getShippingWeight();

        $countries_query  = xtc_db_query(
            sprintf(
                'SELECT *
                   FROM `%s`',
                TABLE_COUNTRIES
            )
        );
        $country_delivery = [];

        while ($country = xtc_db_fetch_array($countries_query)) {
            if ($order->delivery['country']['iso_code_2'] === $country['countries_iso_code_2']) {
                $country_delivery = $country;

                $this->calculations[] = [
                    'item'  => sprintf(
                        'Calculating freight for %s.',
                        $country['countries_iso_code_2']
                    ),
                    'costs' => 0,
                ];

                break;
            }
        }

        $country_configuration           = json_decode(constant(\grandeljayfreight::NAME . '_COUNTRY_' . $country_delivery['countries_iso_code_2']), true);
        $country_ids_with_letter_postals = [
            /** Ireland */
            '103',
            /** Malta */
            '132',
            /** Sweden */
            '222',
            /** United Kingdom */
            '203',
        ];

        $postal_rates  = [];
        $postal_per_kg = 0;

        foreach ($country_configuration as $entry) {
            $postal_from = preg_replace('/[^\d+]+/', '', $entry['postal-from'] ?? 0);
            $postal_to   = preg_replace('/[^\d+]+/', '', $entry['postal-to']   ?? 0);

            if (
                   \is_numeric($postal_from)
                && \is_numeric($postal_to)
                && $order->delivery['postcode'] >= $postal_from
                && $order->delivery['postcode'] <= $postal_to
            ) {
                $postal_rates  = $entry['postal-rates'];
                $postal_per_kg = is_numeric($entry['postal-per-kg']) ? $entry['postal-per-kg'] : 0;

                $this->calculations[] = [
                    'item'  => sprintf(
                        'Postal code %s is >= %s and <= %s.',
                        $order->delivery['postcode'],
                        $postal_from,
                        $postal_to
                    ),
                    'costs' => 0,
                ];

                break;
            } elseif (\in_array($order->delivery['country_id'], $country_ids_with_letter_postals, true)) {
                $postals_from = \explode(',', $entry['postal-from'] ?? '');

                foreach ($postals_from as $postal_area) {
                    if (empty(\trim($postal_area))) {
                        continue;
                    }

                    if (false === \str_starts_with($order->delivery['postcode'], $postal_area)) {
                        continue;
                    }

                    $postal_rates  = $entry['postal-rates'];
                    $postal_per_kg = is_numeric($entry['postal-per-kg']) ? $entry['postal-per-kg'] : 0;

                    $this->calculations[] = [
                        'item'  => sprintf(
                            'Postal code %s has been matched with "%s".',
                            $order->delivery['postcode'],
                            $postal_area
                        ),
                        'costs' => 0,
                    ];
                }
            } else {
                continue;
            }
        }

        foreach ($postal_rates as $rate) {
            if ($rate['weight-max'] >= $shipping_weight) {
                // Use this rate
                $shipping_method_freight['cost'] += $rate['weight-costs'];

                $this->calculations[] = [
                    'item'  => sprintf(
                        'Total weight is <code>%01.2f</code> kg.',
                        $shipping_weight
                    ),
                    'costs' => $rate['weight-costs'],
                ];

                break;
            }
        }

        if ($shipping_method_freight['cost'] <= 0) {
            $shipping_method_freight['cost'] += $postal_per_kg * ceil($shipping_weight);

            $this->calculations[] = [
                'item'  => sprintf(
                    'No matching rate was found for <code>%01.2f</code> kg. Switching to the defined per kg value...',
                    $shipping_weight
                ),
                'costs' => 0,
            ];
            $this->calculations[] = [
                'item'  => sprintf(
                    'Total weight (<code>%01.2f</code> kg) * per kg value (<code>%01.2f</code> €)',
                    ceil($shipping_weight),
                    $postal_per_kg
                ),
                'costs' => $postal_per_kg * ceil($shipping_weight),
            ];
        }

        if ($shipping_method_freight['cost'] <= 0) {
            return $shipping_method_freight;
        }

        /**
         * Add surcharges
         */
        $surcharges_configuration = json_decode(constant(\grandeljayfreight::NAME . '_SURCHARGES_SURCHARGES'), true);

        foreach ($surcharges_configuration as $surcharge) {
            switch ($surcharge['surcharge-type']) {
                case 'surcharge-fixed':
                    $surcharge_amount = $surcharge['surcharge-amount'];
                    break;

                case 'surcharge-percent':
                    $surcharge_amount = $surcharge['surcharge-amount'] / 100 * $order->info['total'];
                    break;
            }

            if ('true' === $surcharge['surcharge-per-pallet']) {
                foreach ($this->pallets as $pallet) {
                    $shipping_method_freight['cost'] += $surcharge_amount;

                    $this->calculations[] = [
                        'item'  => sprintf(
                            'Surcharge "%s" (<code>%01.2f</code> €) is added per pallet.',
                            $surcharge['surcharge-name'],
                            $surcharge_amount,
                        ),
                        'costs' => $surcharge_amount,
                    ];
                }
            } else {
                $shipping_method_freight['cost'] += $surcharge_amount;

                $this->calculations[] = [
                    'item'  => sprintf(
                        'Surcharge "%s" (<code>%01.2f</code> €) is added per order.',
                        $surcharge['surcharge-name'],
                        $surcharge_amount,
                    ),
                    'costs' => $surcharge_amount,
                ];
            }
        }
        /** */

        return $shipping_method_freight;
    }

    private function getNameBoxWeight(): string
    {
        $debug_is_enabled = 'true'; // constant(\grandeljayfreight::NAME . '_DEBUG_ENABLE');
        $user_is_admin    = isset($_SESSION['customers_status']['customers_status_id']) && 0 === (int) $_SESSION['customers_status']['customers_status_id'];

        if ('true' === $debug_is_enabled && $user_is_admin) {
            foreach ($this->methods as &$method) {
                $total = 0;

                ob_start();
                ?>
                <br /><br />

                <h3>Debug mode</h3>
                <style type="text/css">
                    table.calculations :is(th, td).number {
                        text-align: right;
                    }
                </style>
                <table class="calculations">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="number">Costs</th>
                            <th class="number">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($this->calculations as $calculation) { ?>
                            <?php $total += $calculation['costs']; ?>

                            <tr>
                                <td><?= $calculation['item'] ?></td>
                                <td class="number"><code><?= sprintf('%01.2f', $calculation['costs']) ?></code></td>
                                <td class="number"><code><?= sprintf('%01.2f', $total) ?></code></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php
                $method['title'] .= ob_get_clean();
            }
        }

        $pallets_weight = [];

        foreach ($this->pallets as $pallet) {
            $key = $pallet->getWeight() . ' kg';

            if (isset($pallets_weight[$key])) {
                $pallets_weight[$key]++;
            } else {
                $pallets_weight[$key] = 1;
            }
        }

        $boxes_weight_text = [];

        foreach ($pallets_weight as $weight_text => $quantity) {
            preg_match('/[\d+\.]+/', $weight_text, $weight_matches);

            $weight = round($weight_matches[0], 2) . ' kg';

            $boxes_weight_text[] = sprintf(
                '%dx %s',
                $quantity,
                $weight
            );
        }

        if ('true' !== $debug_is_enabled || !$user_is_admin) {
            $boxes_weight_text = [
                sprintf(
                    '%s kg',
                    round($this->getShippingWeight(), 2)
                ),
            ];
        }

        return implode(', ', $boxes_weight_text);
    }

    public function getQuote(): ?array
    {
        if (empty($this->methods) || $this->preceedsMinimumWeight()) {
            return null;
        }

        $quote = [
            'id'      => 'grandeljayfreight',
            'module'  => sprintf(
                'Freight (%s)',
                $this->getNameBoxWeight()
            ),
            'methods' => $this->methods,
        ];

        return $quote;
    }

    public function preceedsMinimumWeight(): bool
    {
        global $order;

        if (null === $order) {
            return true;
        }

        $shipping_weight_min     = constant(\grandeljayfreight::NAME . '_WEIGHT_MINIMUM');
        $preceeds_minimum_weight = $this->getShippingWeight() < $shipping_weight_min;

        return $preceeds_minimum_weight;
    }
}
