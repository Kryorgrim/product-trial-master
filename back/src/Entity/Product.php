<?php

namespace App\Entity;

use App\Dto\Product\Out\ProductDto;
use App\Enum\InventoryStatusEnum;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Map(target: ProductDto::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    public ?int $id = null;

    #[ORM\Column(length: 64)]
    public ?string $code = null;

    #[ORM\Column(length: 64)]
    public ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    public ?string $description = null;

    #[ORM\Column(length: 255)]
    public ?string $image = null;

    #[ORM\Column(length: 64)]
    public ?string $category = null;

    #[ORM\Column(options: ['unsigned' => true])]
    public ?float $price = null;

    #[ORM\Column(options: ['unsigned' => true])]
    public ?int $quantity = null;

    #[ORM\Column(length: 64)]
    public ?string $internalReference = null;

    #[ORM\Column]
    public ?int $shellId = null;

    #[ORM\Column(enumType: InventoryStatusEnum::class)]
    public ?InventoryStatusEnum $inventoryStatus = null;

    #[ORM\Column(type: Types::SMALLINT)]
    public ?int $rating = null;

    #[ORM\Column]
    public ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    public ?\DateTime $updatedAt = null;

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
