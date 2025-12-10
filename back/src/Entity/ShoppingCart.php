<?php

namespace App\Entity;

use App\Repository\ShoppingCartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoppingCartRepository::class)]
class ShoppingCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    public ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'shoppingCart', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    public ?User $user = null;

    /**
     * @var Collection<int, ShoppingCartProduct>
     */
    #[ORM\OneToMany(targetEntity: ShoppingCartProduct::class, mappedBy: 'shoppingCart', orphanRemoval: true, cascade: ['persist'])]
    public Collection $shoppingCartProducts;

    public function __construct()
    {
        $this->shoppingCartProducts = new ArrayCollection();
    }

    public function addShoppingCartProduct(ShoppingCartProduct $shoppingCartProduct): static
    {
        if (!$this->shoppingCartProducts->contains($shoppingCartProduct)) {
            $this->shoppingCartProducts->add($shoppingCartProduct);
            $shoppingCartProduct->shoppingCart = $this;
        }

        return $this;
    }

    public function removeShoppingCartProduct(ShoppingCartProduct $shoppingCartProduct): static
    {
        if ($this->shoppingCartProducts->removeElement($shoppingCartProduct)) {
            // set the owning side to null (unless already changed)
            if ($shoppingCartProduct->shoppingCart === $this) {
                $shoppingCartProduct->shoppingCart = null;
            }
        }

        return $this;
    }
}
