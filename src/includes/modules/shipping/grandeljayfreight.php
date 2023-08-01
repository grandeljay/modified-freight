<?php

/**
 * Freight
 *
 * @author  Jay Trees <freight@grandels.email>
 * @link    https://github.com/grandeljay/modified-freight
 * @package GrandelJayFreight
 *
 * @phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
 * @phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
 */

use Grandeljay\Freight\{Quote, API};
use Grandeljay\Freight\Configuration\Field;
use RobinTheHood\ModifiedStdModule\Classes\StdModule;

class grandeljayfreight extends StdModule
{
    public const NAME    = 'MODULE_SHIPPING_GRANDELJAYFREIGHT';
    public const VERSION = '0.1.1';

    private array $countries;
    public array $quotes;

    public static function setGroup(string $value, string $option): string
    {
        return Field::initialiseGroup($value, $option);
    }

    public static function setFieldCountry(string $value, string $option): string
    {
        return Field::initialiseFieldCountry($value, $option);
    }

    public static function setFieldSurcharges(string $value, string $option): string
    {
        return Field::initialiseFieldSurcharges($value, $option);
    }

    public function __construct()
    {
        parent::__construct(self::NAME);

        $this->checkForUpdate(true);

        $this->initialiseWeight();
        $this->initialiseCountries();
        $this->initialiseSurcharges();

        $this->appendHTML();
    }

    private function initialiseWeight(): void
    {
        $this->addKey('WEIGHT_START');

        $this->addKey('WEIGHT_PER_PALLET');

        $this->addKey('WEIGHT_END');
    }

    private function initialiseCountries(): void
    {
        $countries_query = xtc_db_query(
            sprintf(
                'SELECT *
                   FROM `%s`',
                TABLE_COUNTRIES
            )
        );

        while ($country = xtc_db_fetch_array($countries_query)) {
            $this->countries[] = $country;
        }

        $this->addKey('COUNTRIES_START');

        foreach ($this->countries as $country) {
            $key = sprintf(
                'COUNTRY_%s',
                $country['countries_iso_code_2']
            );

            $this->addKey($key);

            $title       = self::NAME . '_' . $key . '_TITLE';
            $description = self::NAME . '_' . $key . '_DESC';

            if (class_exists('\Locale')) {
                defined($title) || define($title, \Locale::getDisplayRegion('-' . $country['countries_iso_code_2'], $_SESSION['language']));
            } else {
                defined($title) || define($title, $country['countries_name']);
            }

            defined($description) || define($description, $country['countries_name'] . ' / ' . $country['countries_iso_code_2'] . ' / ' . $country['countries_iso_code_3']);
        }

        $this->addKey('COUNTRIES_END');
    }

    private function initialiseSurcharges(): void
    {
        $this->addKey('SURCHARGES_START');

        $this->addKey('SURCHARGES_SURCHARGES');

        $this->addKey('SURCHARGES_END');
    }

    private function appendHTML(): void
    {
        ?>
        <template id="grandeljayfreight_bulk">
            <dialog style="width: 512px;">
                <div class="modulbox">
                    <table class="contentTable">
                        <tbody>
                            <tr class="infoBoxHeading">
                                <td class="infoBoxHeading">
                                    <div class="infoBoxHeadingTitle"><b><?= constant(self::NAME . '_BUTTON_BULK') ?></b></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="contentTable">
                        <tbody>
                            <tr class="infoBoxContent">
                                <td class="infoBoxContent">
                                    <table>
                                        <thead>
                                            <tr>
                                                <td>
                                                    <strong><?= constant(self::NAME . '_BULK_FACTOR_TITLE') ?></strong>
                                                    <p><?= constant(self::NAME . '_BULK_FACTOR_DESC') ?></p>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="number" step="any" data-name="factor" />
                                                    <img src="../images/loadingAnimation.gif" class="loading" />
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <input type="button" class="button" value="<?= constant(\grandeljayfreight::NAME . '_BUTTON_SAVE') ?>" data-name="save" />
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </dialog>
        </template>
        <?php
    }

    public function install()
    {
        parent::install();

        /**
         * Required for modified compatibility
         */
        $this->addConfiguration('ALLOWED', '', 6, 1);
        /** */

        $this->installWeight();
        $this->installCountries();
        $this->installSurcharges();
    }

    private function installWeight(): void
    {
        $this->addConfiguration('WEIGHT_START', '<h2>' . $this->getConfig('WEIGHT_START_TITLE') . '</h2>', 6, 1, self::class . '::setGroup(');

        $this->addConfiguration('WEIGHT_PER_PALLET', 500, 6, 1);

        $this->addConfiguration('WEIGHT_END', '', 6, 1, self::class . '::setGroup(');
    }

    private function installCountries(): void
    {
        $this->addConfiguration('COUNTRIES_START', '<h2>' . $this->getConfig('COUNTRIES_START_TITLE') . '</h2>', 6, 1, self::class . '::setGroup(');

        foreach ($this->countries as $country) {
            $value = match ($country['countries_iso_code_2']) {
                'BE' => array(
                    array(
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 64,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 81,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 97,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 114,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 132,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 153,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 173,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 195,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 216,
                            ),

                        ),
                        'postal-per-kg' => 0.25,
                    ),
                ),
                'BG' => array(
                    array(
                        'postal-from'   => 1000,
                        'postal-to'     => 1900,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 75,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 107,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 135,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 164,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 193,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 219,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 246,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 275,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 306,
                            ),

                        ),
                        'postal-per-kg' => 306 / 890 + 0.01,
                    ),
                    array(
                        'postal-from'   => 21000,
                        'postal-to'     => 23999,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 78,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 110,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 140,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 171,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 200,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 227,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 254,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 283,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 314,
                            ),

                        ),
                        'postal-per-kg' => 314 / 890 + 0.01,
                    ),
                    array(
                        'postal-from'   => 20000,
                        'postal-to'     => 20999,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 81,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 114,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 146,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 177,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 208,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 235,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 263,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 291,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 323,
                            ),

                        ),
                        'postal-per-kg' => 323 / 890 + 0.01,
                    ),
                    array(
                        'postal-from'   => 24000,
                        'postal-to'     => 27999,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 81,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 114,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 146,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 177,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 208,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 235,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 263,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 291,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 323,
                            ),

                        ),
                        'postal-per-kg' => 323 / 890 + 0.01,
                    ),
                    array(
                        'postal-from'   => 30000,
                        'postal-to'     => 31999,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 82,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 118,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 150,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 182,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 212,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 241,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 270,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 299,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 329,
                            ),

                        ),
                        'postal-per-kg' => 329 / 890 + 0.01,
                    ),
                    array(
                        'postal-from'   => 40000,
                        'postal-to'     => 44999,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 82,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 118,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 150,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 182,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 212,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 241,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 270,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 299,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 329,
                            ),

                        ),
                        'postal-per-kg' => 329 / 890 + 0.01,
                    ),
                    array(
                        'postal-from'   => 28000,
                        'postal-to'     => 29999,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 87,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 122,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 156,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 190,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 221,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 248,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 279,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 309,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 342,
                            ),

                        ),
                        'postal-per-kg' => 342 / 890 + 0.01,
                    ),
                    array(
                        'postal-from'   => 32000,
                        'postal-to'     => 39999,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 87,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 122,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 156,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 190,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 221,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 248,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 279,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 309,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 342,
                            ),

                        ),
                        'postal-per-kg' => 342 / 890 + 0.01,
                    ),
                    array(
                        'postal-from'   => 55000,
                        'postal-to'     => 55999,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 87,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 122,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 156,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 190,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 221,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 248,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 279,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 309,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 342,
                            ),

                        ),
                        'postal-per-kg' => 342 / 890 + 0.01,
                    ),
                    array(
                        'postal-from'   => 58000,
                        'postal-to'     => 58999,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 87,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 122,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 156,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 190,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 221,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 248,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 279,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 309,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 342,
                            ),

                        ),
                        'postal-per-kg' => 342 / 890 + 0.01,
                    ),
                    array(
                        'postal-from'   => 45000,
                        'postal-to'     => 51999,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 90,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 128,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 165,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 197,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 229,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 260,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 289,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 320,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 353,
                            ),

                        ),
                        'postal-per-kg' => 353 / 890 + 0.01,
                    ),
                    array(
                        'postal-from'   => 53000,
                        'postal-to'     => 54999,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 90,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 128,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 165,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 197,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 229,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 260,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 289,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 320,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 353,
                            ),

                        ),
                        'postal-per-kg' => 353 / 890 + 0.01,
                    ),
                    array(
                        'postal-from'   => 60000,
                        'postal-to'     => 63999,
                        'postal-rates'  => array(
                            array(
                                'weight-max'   => 90,
                                'weight-costs' => 90,
                            ),
                            array(
                                'weight-max'   => 190,
                                'weight-costs' => 128,
                            ),
                            array(
                                'weight-max'   => 290,
                                'weight-costs' => 165,
                            ),
                            array(
                                'weight-max'   => 390,
                                'weight-costs' => 197,
                            ),
                            array(
                                'weight-max'   => 490,
                                'weight-costs' => 229,
                            ),
                            array(
                                'weight-max'   => 590,
                                'weight-costs' => 260,
                            ),
                            array(
                                'weight-max'   => 690,
                                'weight-costs' => 289,
                            ),
                            array(
                                'weight-max'   => 790,
                                'weight-costs' => 320,
                            ),
                            array(
                                'weight-max'   => 890,
                                'weight-costs' => 353,
                            ),

                        ),
                        'postal-per-kg' => 353 / 890 + 0.01,
                    ),
                ),
                default => array(),
            };

            $this->addConfiguration(
                sprintf(
                    'COUNTRY_%s',
                    $country['countries_iso_code_2']
                ),
                json_encode($value),
                6,
                1,
                self::class . '::setFieldCountry('
            );
        }

        $this->addConfiguration('COUNTRIES_END', '', 6, 1, self::class . '::setGroup(');
    }

    private function installSurcharges(): void
    {
        $this->addConfiguration('SURCHARGES_START', '<h2>' . $this->getConfig('SURCHARGES_START_TITLE') . '</h2>', 6, 1, self::class . '::setGroup(');

        $surcharges = json_encode(
            array(
                array(
                    'surcharge-amount'     => '20',
                    'surcharge-name'       => 'Pick & Pack',
                    'surcharge-per-pallet' => 'true',
                    'surcharge-type'       => 'surcharge-fixed',
                ),
                array(
                    'surcharge-amount'     => '2',
                    'surcharge-name'       => 'Versicherungsprämie',
                    'surcharge-per-pallet' => 'false',
                    'surcharge-type'       => 'surcharge-percent',
                ),
            ),
            JSON_UNESCAPED_UNICODE
        );

        $this->addConfiguration('SURCHARGES_SURCHARGES', $surcharges, 6, 1, self::class . '::setFieldSurcharges(');

        $this->addConfiguration('SURCHARGES_END', '', 6, 1, self::class . '::setGroup(');
    }

    protected function updateSteps()
    {
        if (-1 === version_compare($this->getVersion(), self::VERSION)) {
            $this->setVersion(self::VERSION);

            return self::UPDATE_SUCCESS;
        }

        return self::UPDATE_NOTHING;
    }

    public function remove()
    {
        parent::remove();

        /**
         * Required for modified compatibility
         */
        $this->removeConfiguration('ALLOWED');
        /** */

        $this->removeWeight();
        $this->removeCountries();
        $this->removeSurcharges();
    }

    private function removeWeight(): void
    {
        $this->removeConfiguration('WEIGHT_START');

        $this->removeConfiguration('WEIGHT_PER_PALLET');

        $this->removeConfiguration('WEIGHT_END');
    }

    private function removeCountries(): void
    {
        $this->removeConfiguration('COUNTRIES_START');

        foreach ($this->countries as $country) {
            $this->removeConfiguration(
                sprintf(
                    'COUNTRY_%s',
                    $country['countries_iso_code_2']
                )
            );
        }

        $this->removeConfiguration('COUNTRIES_END');
    }

    private function removeSurcharges(): void
    {
        $this->removeConfiguration('SURCHARGES_START');

        $this->removeConfiguration('SURCHARGES_SURCHARGES');

        $this->removeConfiguration('SURCHARGES_END');
    }

    /**
     * Used by modified to show shipping costs. Will be ignored if the value is
     * not an array.
     *
     * @var ?array
     */
    public function quote(): ?array
    {
        $quote  = new Quote(self::NAME);
        $quotes = $quote->getQuote();

        if (is_array($quotes)) {
            $this->quotes = $quotes;
        }

        return $quotes;
    }
}
