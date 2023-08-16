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
    private float $weight   = 0;
    private array $products = array();

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function addProduct(array $product): void
    {
        $this->products[] = $product['id'];
        $this->weight    += $product['weight'];
    }
}
