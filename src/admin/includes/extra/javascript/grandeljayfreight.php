<?php

/**
 * Freight
 *
 * @author  Jay Trees <freight@grandels.email>
 * @link    https://github.com/grandeljay/modified-freight
 * @package GrandelJayFreight
 */

if (rth_is_module_disabled('MODULE_SHIPPING_GRANDELJAYFREIGHT')) {
    return;
}

/** Only enqueue JavaScript when module settings are open */
$grandeljayfreight_admin_screen = array(
    'set'    => 'shipping',
    'module' => grandeljayfreight::class,
    'action' => 'edit',
);

parse_str($_SERVER['QUERY_STRING'] ?? '', $query_string);

foreach ($grandeljayfreight_admin_screen as $key => $value) {
    if (!isset($query_string[$key]) || $query_string[$key] !== $value) {
        return;
    }
}

/** Enqueue JavaScript */
$files = array(
    grandeljayfreight::class,
    grandeljayfreight::class . '_popup',
);

foreach ($files as $file) {
    $file_name    = '/' . DIR_ADMIN . 'includes/javascript/' . $file . '.js';
    $file_path    = DIR_FS_CATALOG .  $file_name;
    $file_version = hash_file('crc32c', $file_path);
    $file_url     = $file_name . '?v=' . $file_version;
    ?>
    <script type="text/javascript" src="<?= $file_url ?>" defer></script>
    <?php
}
