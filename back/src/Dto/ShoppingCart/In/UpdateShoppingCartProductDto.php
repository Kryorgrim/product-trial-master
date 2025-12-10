<?php

namespace App\Dto\ShoppingCart\In;

use Symfony\Component\Validator\Constraints as Assert;

final class UpdateShoppingCartProductDto
{
    public function __construct(
        #[Assert\NotBlank]
        public int $quantity,
    ) {}
}
