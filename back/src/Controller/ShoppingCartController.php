<?php

namespace App\Controller;

use App\Dto\ShoppingCart\In\AddShoppingCartProductDto;
use App\Dto\ShoppingCart\In\UpdateShoppingCartProductDto;
use App\Dto\ShoppingCart\Out\ShoppingCartDto;
use App\Entity\Product;
use App\Entity\ShoppingCart;
use App\Entity\ShoppingCartProduct;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ShoppingCartController extends AbstractController
{
    #[Route('/shopping-cart', name: 'app_shopping_cart')]
    public function showShoppingCart(
        ObjectMapperInterface $mapper
    ): JsonResponse {
        $cart = $this->getUserShoppingCart();
        $dto = $mapper->map($cart, ShoppingCartDto::class);
        return $this->json($dto);
    }

    #[Route('/shopping-cart/products', name: 'app_add_shopping_cart_product', methods: ['POST'])]
    public function addProduct(
        #[MapRequestPayload]
        AddShoppingCartProductDto $dto,
        EntityManagerInterface $entityManager,
        ObjectMapperInterface $mapper
    )
    {
        $product = $entityManager->getRepository(Product::class)
            ->find($dto->productId);

        if (!$product) {
            throw new UnprocessableEntityHttpException("Product not found");
        }

        $cartProduct = new ShoppingCartProduct();
        $cartProduct->product = $product;
        $cartProduct->quantity = $dto->quantity;

        $cart = $this->getUserShoppingCart();
        $cart->addShoppingCartProduct($cartProduct);

        $entityManager->persist($cart);
        $entityManager->flush();
        
        $res = $mapper->map($cart, ShoppingCartDto::class);

        return $this->json($res, JsonResponse::HTTP_CREATED);
    }

    #[Route('/shopping-cart/products/{cartProduct}', name: 'app_update_shopping_cart_products', methods: ['PATCH'])]
    public function updateProduct(
        ShoppingCartProduct $cartProduct,
        #[MapRequestPayload]
        UpdateShoppingCartProductDto $dto,
        EntityManagerInterface $entityManager,
        ObjectMapperInterface $mapper
    ) {
        $cartProduct->quantity = $dto->quantity;
        $entityManager->persist($cartProduct);
        $entityManager->flush();

        $res = $mapper->map($cartProduct->shoppingCart, ShoppingCartDto::class);

        return $this->json($res);
    }

    #[Route('/shopping-cart/products/{cartProduct}', name: 'app_delete_shopping_cart_products', methods: ['DELETE'])]
    public function deleteProduct(
        ShoppingCartProduct $cartProduct,
        EntityManagerInterface $entityManager,
    ) {
        $entityManager->remove($cartProduct);
        $entityManager->flush();

        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }

    private function getUserShoppingCart(): ShoppingCart
    {
        /** @var \App\Entity\User */
        $user = $this->getUser();

        return $user->getShoppingCart();
    }
}
