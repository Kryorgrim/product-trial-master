<?php

namespace App\Dto\WishList\Out;

use App\Entity\WishList;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\ObjectMapper\Transform\MapCollection;

#[Map(source: WishList::class)]
class WishListDto
{
    public int $id;

    #[Map(transform: new MapCollection())]
    public array $products;
}
