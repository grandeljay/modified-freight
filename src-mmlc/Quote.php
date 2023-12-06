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
    private float $total_weight = 0;
    private array $pallets      = array();
    private array $methods      = array();

    public function __construct(string $module)
    {
        $this->pallets = $this->getPallets();
        $this->methods = $this->getShippingMethods();
    }

    private function getPallets(): array
    {
        global $order;

        $products          = $order->products;
        $pallets           = array();
        $pallet_max_weight = (float) constant(\grandeljayfreight::NAME . '_WEIGHT_PER_PALLET');

        foreach ($products as $product) {
            for ($i = 1; $i <= $product['quantity']; $i++) {
                $product_weight      = (float) $product['weight'];
                $this->total_weight += $product_weight;

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
        $methods        = array();
        $method_freight = $this->getShippingMethodFreight();

        if ($method_freight['cost'] > 0) {
            $methods[] = $method_freight;
        }

        return $methods;
    }

    private function getShippingMethodFreight(): array
    {
        global $order;

        $shipping_method_freight = array(
            'id'    => 'freight',
            'title' => sprintf(
                '%s (%s)' . '<!-- BREAK -->',
                constant(\grandeljayfreight::NAME . '_LONG_DESCRIPTION'),
                $this->getNameBoxWeight()
            ),
            'cost'  => 0,
            'debug' => array(
                'calculations' => array(),
            ),
        );

        $countries_query  = xtc_db_query(
            sprintf(
                'SELECT *
                   FROM `%s`',
                TABLE_COUNTRIES
            )
        );
        $country_delivery = array();

        while ($country = xtc_db_fetch_array($countries_query)) {
            if ($order->delivery['country']['iso_code_2'] === $country['countries_iso_code_2']) {
                $country_delivery                                   = $country;
                $shipping_method_freight['debug']['calculations'][] = 'Calculating freight for ' . $country['countries_iso_code_2'] . '.';

                break;
            }
        }

        $country_configuration = json_decode(constant(\grandeljayfreight::NAME . '_COUNTRY_' . $country_delivery['countries_iso_code_2']), true);

        $postal_rates  = array();
        $postal_per_kg = 0;

        foreach ($country_configuration as $entry) {
            $postal_from = preg_replace('/[^\d+]+/', '', $entry['postal-from'] ?? 0);
            $postal_to   = preg_replace('/[^\d+]+/', '', $entry['postal-to']   ?? 0);

            if (
                   $order->delivery['postcode'] >= $postal_from
                && $order->delivery['postcode'] <= $postal_to
            ) {
                $postal_rates                                       = $entry['postal-rates'];
                $shipping_method_freight['debug']['calculations'][] = sprintf(
                    'Postal code %s is >= %s and <= %s.',
                    $order->delivery['postcode'],
                    $postal_from,
                    $postal_to
                );
                $postal_per_kg                                      = is_numeric($entry['postal-per-kg']) ? $entry['postal-per-kg'] : 0;

                break;
            } else {
                continue;
            }
        }

        foreach ($postal_rates as $rate) {
            if ($rate['weight-max'] >= $this->total_weight) {
                // Use this rate
                $shipping_method_freight['cost']                   += $rate['weight-costs'];
                $shipping_method_freight['debug']['calculations'][] = sprintf(
                    'Total weight is %s kg which costs %s €. (%s € total).',
                    round($this->total_weight, 2),
                    round($rate['weight-costs'], 2),
                    round($shipping_method_freight['cost'], 2)
                );

                break;
            }
        }

        if ($shipping_method_freight['cost'] <= 0) {
            $shipping_method_freight['cost']                   += $postal_per_kg * ceil($this->total_weight);
            $shipping_method_freight['debug']['calculations'][] = sprintf(
                'No matching rate was found for %s kg. Switching to the defined per kg value...',
                round($this->total_weight, 2),
            );
            $shipping_method_freight['debug']['calculations'][] = sprintf(
                'Total weight (%s kg) * per kg value (%s €) = %s €.',
                ceil($this->total_weight),
                round($postal_per_kg, 2),
                round($shipping_method_freight['cost'], 2),
            );
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
                    $shipping_method_freight['cost']                   += $surcharge_amount;
                    $shipping_method_freight['debug']['calculations'][] = sprintf(
                        'Surcharge "%s" (%s €) is added per pallet (%s € total)',
                        $surcharge['surcharge-name'],
                        round($surcharge_amount, 2),
                        round($shipping_method_freight['cost'], 2)
                    );
                }
            } else {
                $shipping_method_freight['cost']                   += $surcharge_amount;
                $shipping_method_freight['debug']['calculations'][] = sprintf(
                    'Surcharge "%s" (%s €) is added per order (%s € total)',
                    $surcharge['surcharge-name'],
                    round($surcharge_amount, 2),
                    round($shipping_method_freight['cost'], 2)
                );
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
                ob_start();
                ?>
                <br /><br />

                <h3>Debug mode</h3>

                <?php foreach ($method['debug']['calculations'] as $calculation) { ?>
                    <p><?= $calculation ?></p>
                <?php } ?>
                <?php
                $method['title'] .= ob_get_clean();
            }
        }

        $pallets_weight = array();

        foreach ($this->pallets as $pallet) {
            $key = $pallet->getWeight() . ' kg';

            if (isset($pallets_weight[$key])) {
                $pallets_weight[$key]++;
            } else {
                $pallets_weight[$key] = 1;
            }
        }

        $boxes_weight_text = array();

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
            $boxes_weight_text = array(
                sprintf(
                    '%s kg',
                    round($this->total_weight, 2)
                ),
            );
        }

        return implode(', ', $boxes_weight_text);
    }

    public function getQuote(): ?array
    {
        if (empty($this->methods) || $this->preceedsMinimumWeight()) {
            return null;
        }

        $quote = array(
            'id'      => 'grandeljayfreight',
            'module'  => sprintf(
                'Freight (%s)',
                $this->getNameBoxWeight()
            ),
            'methods' => $this->methods,
        );

        return $quote;
    }

    public function preceedsMinimumWeight(): bool
    {
        global $order, $total_weight;

        if (null === $order) {
            return true;
        }

        $shipping_weight_min     = constant(\grandeljayfreight::NAME . '_WEIGHT_MINIMUM');
        $preceeds_minimum_weight = $total_weight < $shipping_weight_min;

        return $preceeds_minimum_weight;
    }
}
