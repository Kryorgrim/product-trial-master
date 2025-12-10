<?php

namespace App\Dto\ShoppingCart\Out;

use App\Dto\Product\Out\ProductDto;
use App\Entity\ShoppingCartProduct;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: ShoppingCartProduct::class)]
class ShoppingCartProductDto
{
    public int $id;

    public $product;

    public int $quantity;
}