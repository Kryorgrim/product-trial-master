<?php

namespace App\Dto\Product\In;

use App\Entity\Product;
use App\Enum\InventoryStatusEnum;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: Product::class)]
final class StoreProductDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $code,

        #[Assert\NotBlank]
        public string $name,

        #[Assert\NotBlank]
        public string $description,

        #[Assert\NotBlank]
        #[Assert\Url]
        public string $image,

        #[Assert\NotBlank]
        public string $category,

        #[Assert\NotBlank]
        public float $price,

        #[Assert\NotBlank]
        public int $quantity,

        #[Assert\NotBlank]
        public string $internalReference,

        #[Assert\NotBlank]
        public int $shellId,

        #[Assert\NotBlank]
        #[Assert\Type(InventoryStatusEnum::class)]
        public InventoryStatusEnum $inventoryStatus,

        #[Assert\NotBlank]
        #[Assert\LessThanOrEqual(5)]
        public int $rating,
    ) {}
}
