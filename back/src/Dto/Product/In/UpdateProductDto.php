<?php

namespace App\Dto\Product\In;

use App\Entity\Product;
use App\Enum\InventoryStatusEnum;
use App\ObjectMapper\IsNotNullCondition;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(target: Product::class)]
final class UpdateProductDto
{
    public function __construct(
        #[Map(if: IsNotNullCondition::class)]
        public ?string $code,

        #[Map(if: IsNotNullCondition::class)]
        public ?string $name,

        #[Map(if: IsNotNullCondition::class)]
        public ?string $description,

        #[Assert\Url]
        #[Map(if: IsNotNullCondition::class)]
        public ?string $image,

        #[Map(if: IsNotNullCondition::class)]
        public ?string $category,

        #[Map(if: IsNotNullCondition::class)]
        public ?float $price,

        #[Map(if: IsNotNullCondition::class)]
        public ?int $quantity,

        #[Map(if: IsNotNullCondition::class)]
        public ?string $internalReference,

        #[Map(if: IsNotNullCondition::class)]
        public ?int $shellId,

        #[Assert\Type(InventoryStatusEnum::class)]
        #[Map(if: IsNotNullCondition::class)]
        public ?string $inventoryStatus,

        #[Assert\LessThanOrEqual(5)]
        #[Map(if: IsNotNullCondition::class)]
        public ?int $rating,
    ) {}
}
