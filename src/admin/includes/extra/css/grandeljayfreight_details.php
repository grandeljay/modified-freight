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

/** Only enqueue Stylsheet when module settings are open */
$grandeljayfreight_admin_screen = [
    'set'    => 'shipping',
    'module' => grandeljayfreight::class,
    'action' => 'edit',
];

parse_str($_SERVER['QUERY_STRING'] ?? '', $query_string);

foreach ($grandeljayfreight_admin_screen as $key => $value) {
    if (!isset($query_string[$key]) || $query_string[$key] !== $value) {
        return;
    }
}

/** Enqueue Stylesheet */
$files = [
    grandeljayfreight::class . '_details',
];

foreach ($files as $file) {
    $file_name    = '/' . DIR_ADMIN . 'includes/css/' . $file . '.css';
    $file_path    = DIR_FS_CATALOG .  $file_name;
    $file_version = hash_file('crc32c', $file_path);
    $file_url     = $file_name . '?v=' . $file_version;
    ?>
    <link rel="stylesheet" href="<?= $file_url ?>">
    <?php
}
