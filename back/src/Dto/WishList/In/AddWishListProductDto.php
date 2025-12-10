<?php

namespace App\Dto\WishList\In;

use Symfony\Component\Validator\Constraints as Assert;

final class AddWishListProductDto
{
    public function __construct(
        #[Assert\NotBlank]
        public int $productId,
    ) {}
}
