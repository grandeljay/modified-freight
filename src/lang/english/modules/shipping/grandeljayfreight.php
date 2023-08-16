<?php

/**
 * English translations
 *
 * @author  Jay Trees <freight@grandels.email>
 * @link    https://github.com/grandeljay/modified-freight
 * @package GrandelJayFreight
 */

if (defined('TABLE_COUNTRIES') && defined('MODULE_SHIPPING_GRANDELJAYUPS_SHIPPING_NATIONAL_COUNTRY')) {
    $country_query = xtc_db_query(
        'SELECT *
           FROM `' . TABLE_COUNTRIES . '`
          WHERE `countries_id` = ' . MODULE_SHIPPING_GRANDELJAYUPS_SHIPPING_NATIONAL_COUNTRY
    );
    $country       = xtc_db_fetch_array($country_query);
}

/**
 * General
 */
$translations_general = array(
    /** Module */
    'TITLE'                           => 'grandeljay - Freight',
    'LONG_DESCRIPTION'                => 'Freight',
    'STATUS_TITLE'                    => 'Activate module?',
    'STATUS_DESC'                     => 'Enables shipping via freight.',
    'TEXT_TITLE'                      => 'Freight',
    'SORT_ORDER_TITLE'                => 'Sort order',
    'SORT_ORDER_DESC'                 => 'Determines the sorting in the Admin and Checkout. Lowest numbers are displayed first.',

    /** Interface */
    'POSTAL_FROM_TITLE'               => 'From',
    'POSTAL_FROM_DESC'                => 'From which postcode this entry should apply. Leave blank for all.',
    'POSTAL_TO_TITLE'                 => 'Until',
    'POSTAL_TO_DESC'                  => 'Up to which postcode this entry should apply. Leave blank for all.',
    'POSTAL_RATES_TITLE'              => 'Tariffs',
    'POSTAL_RATES_DESC'               => 'Rate table for this entry.',
    'POSTAL_RATES_WEIGHT_MAX_TITLE'   => 'Kg',
    'POSTAL_RATES_WEIGHT_MAX_DESC'    => 'Maximum weight allowed for the price indicated.',
    'POSTAL_RATES_WEIGHT_COSTS_TITLE' => 'Costs',
    'POSTAL_RATES_WEIGHT_COSTS_DESC'  => 'Cost for the specified weight.',
    'POSTAL_PER_KG_TITLE'             => 'Price per kilogram',
    'POSTAL_PER_KG_DESC'              => 'Applies only from the defined maximum weight (e.g. 890 kg).',

    'BUTTON_ADD'                      => 'Add',
    'BUTTON_APPLY'                    => 'Take over',
    'BUTTON_SAVE'                     => 'Save',
    'BUTTON_BULK'                     => 'Adjust prices in bulk',
    'BUTTON_CANCEL'                   => 'Cancel',

    /** Required for modified compatibility */
    'ALLOWED_TITLE'                   => '',
    'ALLOWED_DESC'                    => '',

    /** Debug */
    'DEBUG_ENABLE_TITLE'              => 'Debug mode',
    'DEBUG_ENABLE_DESC'               => 'Activate debug mode? Additional information is displayed, e.g. how the shipping costs were calculated. Only visible for admins.',

    /** Bulk */
    'BULK_FACTOR_TITLE'               => 'Factor',
    'BULK_FACTOR_DESC'                => 'By what factor should all tariffs for this country be adjusted?',
);

$translations_groups = array(
    /** Weight */
    'WEIGHT_START_TITLE'                    => 'Weight',
    'WEIGHT_START_DESC'                     => '',

    'WEIGHT_PER_PALLET_TITLE'               => 'Maximum weight per pallet',
    'WEIGHT_PER_PALLET_DESC'                => 'What is the maximum weight of a pallet?',

    'WEIGHT_END_TITLE'                      => '',
    'WEIGHT_END_DESC'                       => '',

    /** Countries */
    'COUNTRIES_START_TITLE'                 => 'Country tariffs',
    'COUNTRIES_START_DESC'                  => 'Includes a list of all countries and their tariffs.',
    'COUNTRIES_END_TITLE'                   => '',
    'COUNTRIES_END_DESC'                    => '',

    /** Surcharges */
    'SURCHARGES_START_TITLE'                => 'Surcharges',
    'SURCHARGES_START_DESC'                 => '',

    'SURCHARGES_SURCHARGES_TITLE'           => 'Surcharges',
    'SURCHARGES_SURCHARGES_DESC'            => '',

    'SURCHARGES_SURCHARGE_NAME_TITLE'       => 'Name',
    'SURCHARGES_SURCHARGE_NAME_DESC'        => 'Term for the serve.',
    'SURCHARGES_SURCHARGE_AMOUNT_TITLE'     => 'Impact',
    'SURCHARGES_SURCHARGE_AMOUNT_DESC'      => 'How high should the mark-up be?',
    'SURCHARGES_SURCHARGE_TYPE_TITLE'       => 'Art',
    'SURCHARGES_SURCHARGE_TYPE_DESC'        => 'What kind of surcharge are we talking about?',
    'SURCHARGES_SURCHARGE_TYPE_FIXED'       => 'Fixed',
    'SURCHARGES_SURCHARGE_TYPE_PERCENT'     => 'Percentage',
    'SURCHARGES_SURCHARGE_PER_PALETT_TITLE' => 'Per pallet',
    'SURCHARGES_SURCHARGE_PER_PALETT_DESC'  => 'Should the surcharge be calculated per pallet?',

    'SURCHARGES_END_TITLE'                  => '',
    'SURCHARGES_END_DESC'                   => '',
);

/**
 * Define
 */
$translations = array_merge(
    $translations_general,
    $translations_groups,
);

foreach ($translations as $key => $value) {
    $constant = 'MODULE_SHIPPING_' . strtoupper(pathinfo(__FILE__, PATHINFO_FILENAME)) . '_' . $key;

    define($constant, $value);
}
