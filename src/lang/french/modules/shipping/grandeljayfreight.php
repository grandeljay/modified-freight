<?php

/**
 * French translations
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
$translations_general = [
    /** Module */
    'TITLE'                           => 'grandeljay - Expédition',
    'LONG_DESCRIPTION'                => 'Expédition de marchandises',
    'STATUS_TITLE'                    => 'Activer le module ?',
    'STATUS_DESC'                     => 'Permet l\'expédition via un transporteur.',
    'TEXT_TITLE'                      => 'Expédition',
    'SORT_ORDER_TITLE'                => 'Ordre de tri',
    'SORT_ORDER_DESC'                 => 'Détermine le tri dans Admin et Checkout. Les chiffres les plus bas sont affichés en premier.',

    /** Interface */
    'POSTAL_FROM_TITLE'               => 'À partir de',
    'POSTAL_FROM_DESC'                => 'A partir de quel code postal cette entrée doit être valable. Laisser vide pour tous.',
    'POSTAL_TO_TITLE'                 => 'Jusqu\'à',
    'POSTAL_TO_DESC'                  => 'Jusqu\'à quel code postal cette entrée doit être valable. Laissez vide pour tous.',
    'POSTAL_RATES_TITLE'              => 'Tarifs',
    'POSTAL_RATES_DESC'               => 'Tableau des tarifs pour cette entrée.',
    'POSTAL_RATES_WEIGHT_MAX_TITLE'   => 'Kg',
    'POSTAL_RATES_WEIGHT_MAX_DESC'    => 'Poids maximum autorisé pour le prix indiqué.',
    'POSTAL_RATES_WEIGHT_COSTS_TITLE' => 'Coûts',
    'POSTAL_RATES_WEIGHT_COSTS_DESC'  => 'Coût pour le poids indiqué.',
    'POSTAL_PER_KG_TITLE'             => 'Prix au kilogramme',
    'POSTAL_PER_KG_DESC'              => 'S\'applique uniquement à partir du poids maximum défini (par exemple 890 kg).',

    'BUTTON_ADD'                      => 'Ajouter',
    'BUTTON_APPLY'                    => 'Reprendre',
    'BUTTON_SAVE'                     => 'Enregistrer',
    'BUTTON_BULK'                     => 'Ajuster les prix en vrac',
    'BUTTON_CANCEL'                   => 'Annuler',

    /** Required for modified compatibility */
    'ALLOWED_TITLE'                   => '',
    'ALLOWED_DESC'                    => '',

    /** Debug */
    'DEBUG_ENABLE_TITLE'              => 'Mode de débogage',
    'DEBUG_ENABLE_DESC'               => 'Activer le mode de débogage ? Des informations supplémentaires sont affichées, par exemple comment les frais de port ont été calculés. Visible uniquement par les admins.',

    /** Bulk */
    'BULK_FACTOR_TITLE'               => 'Facteur',
    'BULK_FACTOR_DESC'                => 'Par quel facteur tous les tarifs doivent-ils être ajustés pour ce pays ?',
];

$translations_groups = [
    /** Weight */
    'WEIGHT_START_TITLE'                    => 'Poids',
    'WEIGHT_START_DESC'                     => '',

    'WEIGHT_PER_PALLET_TITLE'               => 'Poids maximal par palette',
    'WEIGHT_PER_PALLET_DESC'                => 'Quel est le poids maximal d\'une palette ?',
    'WEIGHT_MINIMUM_TITLE'                  => 'Poids minimal',
    'WEIGHT_MINIMUM_DESC'                   => 'le poids d\'expédition à partir duquel le mode d\'expédition est affiché.',

    'WEIGHT_END_TITLE'                      => '',
    'WEIGHT_END_DESC'                       => '',

    /** Countries */
    'COUNTRIES_START_TITLE'                 => 'Tarifs par pays',
    'COUNTRIES_START_DESC'                  => 'Contient une liste de tous les pays et de leurs tarifs.',
    'COUNTRIES_END_TITLE'                   => '',
    'COUNTRIES_END_DESC'                    => '',

    /** Surcharges */
    'SURCHARGES_START_TITLE'                => 'Suppléments',
    'SURCHARGES_START_DESC'                 => '',

    'SURCHARGES_SURCHARGES_TITLE'           => 'Suppléments',
    'SURCHARGES_SURCHARGES_DESC'            => '',

    'SURCHARGES_SURCHARGE_NAME_TITLE'       => 'Nom',
    'SURCHARGES_SURCHARGE_NAME_DESC'        => 'Terme désignant le service.',
    'SURCHARGES_SURCHARGE_AMOUNT_TITLE'     => 'Service',
    'SURCHARGES_SURCHARGE_AMOUNT_DESC'      => 'Quel doit être le montant de la majoration ?',
    'SURCHARGES_SURCHARGE_TYPE_TITLE'       => 'Art',
    'SURCHARGES_SURCHARGE_TYPE_DESC'        => 'De quelle majoration s\'agit-il ?',
    'SURCHARGES_SURCHARGE_TYPE_FIXED'       => 'Fixe',
    'SURCHARGES_SURCHARGE_TYPE_PERCENT'     => 'Pourcentage',
    'SURCHARGES_SURCHARGE_PER_PALETT_TITLE' => 'Par palette',
    'SURCHARGES_SURCHARGE_PER_PALETT_DESC'  => 'La majoration doit-elle être calculée par palette ?',

    'SURCHARGES_END_TITLE'                  => '',
    'SURCHARGES_END_DESC'                   => '',
];

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
