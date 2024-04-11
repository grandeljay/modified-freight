<?php

/**
 * Italian translations
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
    'TITLE'                           => 'grandeljay - Spedizione merci',
    'LONG_DESCRIPTION'                => 'Spedizione merci',
    'STATUS_TITLE'                    => 'Attivare il modulo?',
    'STATUS_DESC'                     => 'Consente la spedizione tramite spedizioniere.',
    'TEXT_TITLE'                      => 'Spedizione merci',
    'SORT_ORDER_TITLE'                => 'Ordinamento',
    'SORT_ORDER_DESC'                 => 'Determina l\'ordinamento nell\'Admin e nel Checkout. I numeri più bassi vengono visualizzati per primi.',

    /** Interface */
    'POSTAL_FROM_TITLE'               => 'Da',
    'POSTAL_FROM_DESC'                => 'Da quale codice postale deve essere applicata questa voce. Lasciare vuoto per tutti.',
    'POSTAL_TO_TITLE'                 => 'Fino a quando',
    'POSTAL_TO_DESC'                  => 'Fino a quale codice postale deve essere applicata questa voce. Lasciare vuoto per tutti.',
    'POSTAL_RATES_TITLE'              => 'Tariffe',
    'POSTAL_RATES_DESC'               => 'Tabella delle tariffe per questa voce.',
    'POSTAL_RATES_WEIGHT_MAX_TITLE'   => 'Kg',
    'POSTAL_RATES_WEIGHT_MAX_DESC'    => 'Peso massimo consentito per il prezzo indicato.',
    'POSTAL_RATES_WEIGHT_COSTS_TITLE' => 'Costi',
    'POSTAL_RATES_WEIGHT_COSTS_DESC'  => 'Costo per il peso specificato.',
    'POSTAL_PER_KG_TITLE'             => 'Prezzo al chilogrammo',
    'POSTAL_PER_KG_DESC'              => 'Si applica solo a partire dal peso massimo definito (ad esempio, 890 kg).',

    'BUTTON_ADD'                      => 'Aggiungi',
    'BUTTON_APPLY'                    => 'Prendi il testimone',
    'BUTTON_SAVE'                     => 'Risparmiare',
    'BUTTON_BULK'                     => 'Adeguare i prezzi in blocco',
    'BUTTON_CANCEL'                   => 'Annullamento',

    /** Required for modified compatibility */
    'ALLOWED_TITLE'                   => '',
    'ALLOWED_DESC'                    => '',

    /** Debug */
    'DEBUG_ENABLE_TITLE'              => 'Modalità Debug',
    'DEBUG_ENABLE_DESC'               => 'Attivare la modalità di debug? Vengono visualizzate informazioni aggiuntive, ad esempio come sono stati calcolati i costi di spedizione. Visibile solo per gli amministratori.',

    /** Bulk */
    'BULK_FACTOR_TITLE'               => 'Fattore',
    'BULK_FACTOR_DESC'                => 'In base a quale fattore dovrebbero essere adeguate tutte le tariffe per questo Paese?',
];

$translations_groups = [
    /** Weight */
    'WEIGHT_START_TITLE'                    => 'Peso',
    'WEIGHT_START_DESC'                     => '',

    'WEIGHT_PER_PALLET_TITLE'               => 'Peso massimo per pallet',
    'WEIGHT_PER_PALLET_DESC'                => 'Qual è il peso massimo di un pallet?',
    'WEIGHT_MINIMUM_TITLE'                  => 'Peso minimo',
    'WEIGHT_MINIMUM_DESC'                   => 'Da quale peso di spedizione viene visualizzato il metodo di spedizione.',

    'WEIGHT_END_TITLE'                      => '',
    'WEIGHT_END_DESC'                       => '',

    /** Countries */
    'COUNTRIES_START_TITLE'                 => 'Tariffe del Paese',
    'COUNTRIES_START_DESC'                  => 'Include un elenco di tutti i Paesi e delle loro tariffe.',
    'COUNTRIES_END_TITLE'                   => '',
    'COUNTRIES_END_DESC'                    => '',

    /** Surcharges */
    'SURCHARGES_START_TITLE'                => 'Impatti',
    'SURCHARGES_START_DESC'                 => '',

    'SURCHARGES_SURCHARGES_TITLE'           => 'Impatti',
    'SURCHARGES_SURCHARGES_DESC'            => '',

    'SURCHARGES_SURCHARGE_NAME_TITLE'       => 'Nome',
    'SURCHARGES_SURCHARGE_NAME_DESC'        => 'Termine per il servizio.',
    'SURCHARGES_SURCHARGE_AMOUNT_TITLE'     => 'Impatto',
    'SURCHARGES_SURCHARGE_AMOUNT_DESC'      => 'Quanto dovrebbe essere alto il ricarico?',
    'SURCHARGES_SURCHARGE_TYPE_TITLE'       => 'Arte',
    'SURCHARGES_SURCHARGE_TYPE_DESC'        => 'Di che tipo di sovrapprezzo stiamo parlando?',
    'SURCHARGES_SURCHARGE_TYPE_FIXED'       => 'Fisso',
    'SURCHARGES_SURCHARGE_TYPE_PERCENT'     => 'Percentuale',
    'SURCHARGES_SURCHARGE_PER_PALETT_TITLE' => 'Per pallet',
    'SURCHARGES_SURCHARGE_PER_PALETT_DESC'  => 'Il supplemento deve essere calcolato per pallet?',

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
