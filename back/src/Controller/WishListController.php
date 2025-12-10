<?php

namespace App\Controller;

use App\Dto\WishList\In\AddWishListProductDto;
use App\Dto\WishList\Out\WishListDto;
use App\Entity\Product;
use App\Entity\WishList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Routing\Attribute\Route;

final class WishListController extends AbstractController
{
    #[Route('/wish-list', name: 'app_wish_list')]
    public function index(
        ObjectMapperInterface $mapper
    ): JsonResponse {
        $wishList = $this->getUserWishList();
        $dto = $mapper->map($wishList, WishListDto::class);

        return $this->json($dto);
    }

    #[Route('/wish-list/products', name: 'app_add_wish_list_product', methods: 'POST')]
    public function addProduct(
        #[MapRequestPayload]
        AddWishListProductDto $dto,
        EntityManagerInterface $entityManager,
        ObjectMapperInterface $mapper,
    ): JsonResponse {
        $product = $entityManager->getRepository(Product::class)
            ->find($dto->productId);

        if (!$product) {
            throw new UnprocessableEntityHttpException("Product not found");
        }

        $wishList = $this->getUserWishList();

        $wishList->addProduct($product);
        $entityManager->persist($wishList);
        $entityManager->flush();

        $dto = $mapper->map($wishList, WishListDto::class);

        return $this->json($dto);
    }

    #[Route('/wish-list/products/{product}', name: 'app_remove_wish_list_product', methods: 'DELETE')]
    public function removeProduct(
        Product $product,
        EntityManagerInterface $entityManager,
        ObjectMapperInterface $mapper,
    ): JsonResponse {
        $wishList = $this->getUserWishList();

        $wishList->removeProduct($product);
        $entityManager->persist($wishList);
        $entityManager->flush();

        $dto = $mapper->map($wishList, WishListDto::class);

        return $this->json($dto);
    }

    private function getUserWishList(): WishList
    {
        /** @var \App\Entity\User */
        $user = $this->getUser();

        return $user->getWishList();
    }
}
