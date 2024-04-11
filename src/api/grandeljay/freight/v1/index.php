<?php

/**
 * Freight
 *
 * @author  Jay Trees <freight@grandels.email>
 * @link    https://github.com/grandeljay/modified-freight
 * @package GrandelJayFreight
 */

chdir('../../../../');
require 'includes/application_top.php';

if (rth_is_module_disabled('MODULE_SHIPPING_GRANDELJAYFREIGHT')) {
    http_response_code(403);
    return;
}

$_POST    = json_decode(file_get_contents('php://input'), true);
$response = [
    'success' => false,
];

if ('POST' === $_SERVER['REQUEST_METHOD'] && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'bulk':
            $option  = $_POST['body']['option'];
            $factor  = $_POST['body']['factor'];
            $country = json_decode(constant($option), true);

            foreach ($country as &$postal) {
                foreach ($postal['postal-rates'] as &$weight) {
                    $weight['weight-costs'] *= $factor;
                }
            }

            xtc_db_query(
                'UPDATE `configuration`
                    SET `configuration_value` = "' . addslashes(json_encode($country)) . '"
                  WHERE `configuration_key`   = "' . $option . '"'
            );

            $response['success'] = true;
            break;

        default:
            http_response_code(404);
            return;
            break;
    }
}

header('Content-Type: application/json;charset=utf-8');

echo json_encode($response);
