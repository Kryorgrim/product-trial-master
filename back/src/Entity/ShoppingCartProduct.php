<?php

namespace App\Entity;

use App\Dto\ShoppingCart\Out\ShoppingCartProductDto;
use App\Repository\ShoppingCartProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[ORM\Entity(repositoryClass: ShoppingCartProductRepository::class)]
#[ORM\UniqueConstraint(columns: ['shopping_cart_id', 'product_id'])]
#[Map(target: ShoppingCartProductDto::class)]
class ShoppingCartProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    public ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'shoppingCartProducts')]
    #[ORM\JoinColumn(nullable: false)]
    public ?ShoppingCart $shoppingCart = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    public ?Product $product = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    public ?int $quantity = null;
}
