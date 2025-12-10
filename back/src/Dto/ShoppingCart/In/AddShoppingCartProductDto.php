<?php

namespace App\Dto\ShoppingCart\In;

use Symfony\Component\Validator\Constraints as Assert;

final class AddShoppingCartProductDto
{
    public function __construct(
        #[Assert\NotBlank]
        public int $productId,
        
        #[Assert\NotBlank]
        public int $quantity,
    ) {}
}
