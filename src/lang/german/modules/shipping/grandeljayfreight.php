<?php

/**
 * German translations
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
    'TITLE'                           => 'grandeljay - Spedition',
    'LONG_DESCRIPTION'                => 'Speditionsversand',
    'STATUS_TITLE'                    => 'Modul aktivieren?',
    'STATUS_DESC'                     => 'Ermöglicht den Versand via Spedition.',
    'TEXT_TITLE'                      => 'Spedition',
    'SORT_ORDER_TITLE'                => 'Sortierreihenfolge',
    'SORT_ORDER_DESC'                 => 'Bestimmt die Sortierung im Admin und Checkout. Niedrigste Zahlen werden zuerst angezeigt.',

    /** Interface */
    'POSTAL_FROM_TITLE'               => 'Ab',
    'POSTAL_FROM_DESC'                => 'Ab welcher Postleitzahl dieser Eintrag gelten soll. Leer lassen für alle.',
    'POSTAL_TO_TITLE'                 => 'Bis',
    'POSTAL_TO_DESC'                  => 'Bis welcher Postleitzahl dieser Eintrag gelten soll. Leer lassen für alle.',
    'POSTAL_RATES_TITLE'              => 'Tarife',
    'POSTAL_RATES_DESC'               => 'Tariftabelle für diesen Eintrag.',
    'POSTAL_RATES_WEIGHT_MAX_TITLE'   => 'Kg',
    'POSTAL_RATES_WEIGHT_MAX_DESC'    => 'Maximal zulässige Gewicht für den angegebenen Preis.',
    'POSTAL_RATES_WEIGHT_COSTS_TITLE' => 'Kosten',
    'POSTAL_RATES_WEIGHT_COSTS_DESC'  => 'Kosten für das angegebene Gewicht.',
    'POSTAL_PER_KG_TITLE'             => 'Kilogramm-Preis',
    'POSTAL_PER_KG_DESC'              => 'Gilt erst ab dem definiertem Maximalgewicht (z. B. 890 Kg)',

    'BUTTON_ADD'                      => 'Hinzufügen',
    'BUTTON_APPLY'                    => 'Übernehmen',
    'BUTTON_SAVE'                     => 'Speichern',
    'BUTTON_BULK'                     => 'Preise in bulk anpassen',
    'BUTTON_CANCEL'                   => 'Abbrechen',

    /** Required for modified compatibility */
    'ALLOWED_TITLE'                   => '',
    'ALLOWED_DESC'                    => '',

    /** Debug */
    'DEBUG_ENABLE_TITLE'              => 'Debug-Modus',
    'DEBUG_ENABLE_DESC'               => 'Debug-Modus aktivieren? Es werden zusätzliche Informationen angezeigt z. B. wie die Versandkosten errechnet wurden. Nur für Admins sichtbar.',

    /** Bulk */
    'BULK_FACTOR_TITLE'               => 'Faktor',
    'BULK_FACTOR_DESC'                => 'Um welchen Faktor sollen alle Tarife für dieses Land angepasst werden?',
];

$translations_groups = [
    /** Weight */
    'WEIGHT_START_TITLE'                    => 'Gewicht',
    'WEIGHT_START_DESC'                     => '',

    'WEIGHT_PER_PALLET_TITLE'               => 'Maximalgewicht pro Palette',
    'WEIGHT_PER_PALLET_DESC'                => 'Wie viel darf eine Palette maximal wiegen?',
    'WEIGHT_MINIMUM_TITLE'                  => 'Minimalgewicht',
    'WEIGHT_MINIMUM_DESC'                   => 'Ab welchem Versandgewicht die Versandart angezeigt wird.',

    'WEIGHT_END_TITLE'                      => '',
    'WEIGHT_END_DESC'                       => '',

    /** Countries */
    'COUNTRIES_START_TITLE'                 => 'Ländertarife',
    'COUNTRIES_START_DESC'                  => 'Beinhaltet eine Liste aller Länder und deren Tarife.',
    'COUNTRIES_END_TITLE'                   => '',
    'COUNTRIES_END_DESC'                    => '',

    /** Surcharges */
    'SURCHARGES_START_TITLE'                => 'Aufschläge',
    'SURCHARGES_START_DESC'                 => '',

    'SURCHARGES_SURCHARGES_TITLE'           => 'Aufschläge',
    'SURCHARGES_SURCHARGES_DESC'            => '',

    'SURCHARGES_SURCHARGE_NAME_TITLE'       => 'Name',
    'SURCHARGES_SURCHARGE_NAME_DESC'        => 'Bezeichnung für den Aufschlag.',
    'SURCHARGES_SURCHARGE_AMOUNT_TITLE'     => 'Aufschlag',
    'SURCHARGES_SURCHARGE_AMOUNT_DESC'      => 'Wie hoch soll der Aufschlag sein?',
    'SURCHARGES_SURCHARGE_TYPE_TITLE'       => 'Art',
    'SURCHARGES_SURCHARGE_TYPE_DESC'        => 'Um was für einen Aufschlag handelt es sich?',
    'SURCHARGES_SURCHARGE_TYPE_FIXED'       => 'Fest',
    'SURCHARGES_SURCHARGE_TYPE_PERCENT'     => 'Prozentual',
    'SURCHARGES_SURCHARGE_PER_PALETT_TITLE' => 'Pro Palette',
    'SURCHARGES_SURCHARGE_PER_PALETT_DESC'  => 'Soll der Aufschlag pro Palette berechnet werden?',

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
