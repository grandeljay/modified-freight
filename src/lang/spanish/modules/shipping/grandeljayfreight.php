<?php

/**
 * Spanish translations
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
    'TITLE'                           => 'grandeljay - Transporte de mercancías',
    'LONG_DESCRIPTION'                => 'Transporte de mercancías',
    'STATUS_TITLE'                    => '¿Activar módulo?',
    'STATUS_DESC'                     => 'Permite el envío a través de una agencia de transportes.',
    'TEXT_TITLE'                      => 'Transporte de mercancías',
    'SORT_ORDER_TITLE'                => 'Orden de clasificación',
    'SORT_ORDER_DESC'                 => 'Determina la clasificación en Admin y Checkout. Los números más bajos se muestran primero.',

    /** Interface */
    'POSTAL_FROM_TITLE'               => 'En',
    'POSTAL_FROM_DESC'                => 'A partir de qué código postal debe aplicarse esta entrada. Dejar en blanco para todos.',
    'POSTAL_TO_TITLE'                 => 'Hasta',
    'POSTAL_TO_DESC'                  => 'Hasta qué código postal debe aplicarse esta entrada. Dejar en blanco para todos.',
    'POSTAL_RATES_TITLE'              => 'Aranceles',
    'POSTAL_RATES_DESC'               => 'Tabla de tarifas para esta entrada.',
    'POSTAL_RATES_WEIGHT_MAX_TITLE'   => 'Kg',
    'POSTAL_RATES_WEIGHT_MAX_DESC'    => 'Peso máximo permitido para el precio indicado.',
    'POSTAL_RATES_WEIGHT_COSTS_TITLE' => 'Costes',
    'POSTAL_RATES_WEIGHT_COSTS_DESC'  => 'Coste para el peso especificado.',
    'POSTAL_PER_KG_TITLE'             => 'Precio por kilogramo',
    'POSTAL_PER_KG_DESC'              => 'Sólo se aplica a partir del peso máximo definido (por ejemplo, 890 kg).',

    'BUTTON_ADD'                      => 'Añada',
    'BUTTON_APPLY'                    => 'Asumir',
    'BUTTON_SAVE'                     => 'Guarde',
    'BUTTON_BULK'                     => 'Ajustar los precios al por mayor',
    'BUTTON_CANCEL'                   => 'Cancelar',

    /** Required for modified compatibility */
    'ALLOWED_TITLE'                   => '',
    'ALLOWED_DESC'                    => '',

    /** Debug */
    'DEBUG_ENABLE_TITLE'              => 'Modo depuración',
    'DEBUG_ENABLE_DESC'               => '¿Activar el modo de depuración? Se muestra información adicional, por ejemplo, cómo se han calculado los gastos de envío. Sólo visible para los administradores.',

    /** Bulk */
    'BULK_FACTOR_TITLE'               => 'Factor',
    'BULK_FACTOR_DESC'                => '¿En qué factor deberían ajustarse todos los aranceles para este país?',
);

$translations_groups = array(
    /** Weight */
    'WEIGHT_START_TITLE'                    => 'Peso',
    'WEIGHT_START_DESC'                     => '',

    'WEIGHT_PER_PALLET_TITLE'               => 'Peso máximo por paleta',
    'WEIGHT_PER_PALLET_DESC'                => '¿Cuál es el peso máximo de una paleta?',

    'WEIGHT_END_TITLE'                      => '',
    'WEIGHT_END_DESC'                       => '',

    /** Countries */
    'COUNTRIES_START_TITLE'                 => 'Aranceles nacionales',
    'COUNTRIES_START_DESC'                  => 'Incluye una lista de todos los países y sus aranceles.',
    'COUNTRIES_END_TITLE'                   => '',
    'COUNTRIES_END_DESC'                    => '',

    /** Surcharges */
    'SURCHARGES_START_TITLE'                => 'Impactos',
    'SURCHARGES_START_DESC'                 => '',

    'SURCHARGES_SURCHARGES_TITLE'           => 'Impactos',
    'SURCHARGES_SURCHARGES_DESC'            => '',

    'SURCHARGES_SURCHARGE_NAME_TITLE'       => 'Nombre',
    'SURCHARGES_SURCHARGE_NAME_DESC'        => 'Término para el saque.',
    'SURCHARGES_SURCHARGE_AMOUNT_TITLE'     => 'Impacto',
    'SURCHARGES_SURCHARGE_AMOUNT_DESC'      => '¿Cuál debe ser el margen de beneficio?',
    'SURCHARGES_SURCHARGE_TYPE_TITLE'       => 'Arte',
    'SURCHARGES_SURCHARGE_TYPE_DESC'        => '¿De qué tipo de recargo estamos hablando?',
    'SURCHARGES_SURCHARGE_TYPE_FIXED'       => 'Fijo',
    'SURCHARGES_SURCHARGE_TYPE_PERCENT'     => 'Porcentaje',
    'SURCHARGES_SURCHARGE_PER_PALETT_TITLE' => 'Por paleta',
    'SURCHARGES_SURCHARGE_PER_PALETT_DESC'  => '¿El recargo debe calcularse por palé?',

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
