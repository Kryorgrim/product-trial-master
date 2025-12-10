<?php

namespace App\Dto\ShoppingCart\Out;

use App\Entity\ShoppingCart;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\ObjectMapper\Transform\MapCollection;

#[Map(source: ShoppingCart::class)]
class ShoppingCartDto
{
    public int $id;

    #[Map(transform: new MapCollection(), source: 'shoppingCartProducts')]
    public array $products;
}
