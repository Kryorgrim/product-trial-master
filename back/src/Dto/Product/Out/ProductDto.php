<?php

namespace App\Dto\Product\Out;

use App\Enum\InventoryStatusEnum;
use DateTime;
use DateTimeImmutable;

class ProductDto
{
    public int $id;

    public string $code;

    public string $name;

    public string $description;

    public string $image;

    public string $category;

    public float $price;

    public int $quantity;

    public string $internalReference;

    public int $shellId;

    public InventoryStatusEnum $inventoryStatus;

    public int $rating;

    public DateTimeImmutable $createdAt;

    public DateTime $updatedAt;
}