<?php

/**
 * Pallet
 *
 * @author  Jay Trees <freight@grandels.email>
 * @link    https://github.com/grandeljay/modified-freight
 * @package GrandelJayFreight
 */

namespace Grandeljay\Freight;

class Pallet
{
    private array $products = [];

    public function getWeight(): float
    {
        $pallet_weight = 0;

        foreach ($this->products as $product) {
            $products_length = $product['length'] ?? 0;
            $products_width  = $product['width']  ?? 0;
            $products_height = $product['height'] ?? 0;
            $products_weight = $product['weight'] ?? 0;

            if ($products_length > 0 && $products_width > 0 && $products_height > 0) {
                $volumetric_weight = ($products_length * $products_width * $products_height) / 5000;

                if ($volumetric_weight > $products_weight) {
                    $pallet_weight += $volumetric_weight;
                } else {
                    $pallet_weight += $products_weight;
                }
            } else {
                $pallet_weight += $products_weight;
            }
        }

        return $pallet_weight;
    }

    public function addProduct(array $product): void
    {
        $this->products[] = $product;
    }
}
