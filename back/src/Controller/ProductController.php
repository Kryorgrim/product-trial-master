<?php

namespace App\Controller;

use App\Dto\Product\In\StoreProductDto;
use App\Dto\Product\In\UpdateProductDto;
use App\Dto\Product\Out\ProductDto;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/products', 'api_products_')]
final class ProductController extends AbstractController
{
    #[Route('', name: 'index', methods: 'GET')]
    public function index(
        EntityManagerInterface $entityManager,
        ObjectMapperInterface $mapper
    ): JsonResponse {
        $products = $entityManager->getRepository(Product::class)
            ->findAll();

        $dtos = array_map(
            fn($product) => $mapper->map($product, ProductDto::class),
            $products
        );
        return $this->json($dtos);
    }

    #[Route('/{product}', name: 'show', methods: 'GET')]
    public function show(Product $product, ObjectMapperInterface $mapper): JsonResponse
    {
        return $this->json(
            $mapper->map($product, ProductDto::class)
        );
    }

    #[Route('', name: 'store', methods: 'POST')]
    #[IsGranted('admin')]
    public function store(
        #[MapRequestPayload] StoreProductDto $productDto,
        ObjectMapperInterface $mapper,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $product = $mapper->map($productDto, Product::class);

        $entityManager->persist($product);
        $entityManager->flush();

        $dto = $mapper->map($product, ProductDto::class);

        return $this->json($dto);
    }

    #[Route('/{product}', name: 'update', methods: 'PATCH')]
    #[IsGranted('admin')]
    public function update(
        Product $product,
        #[MapRequestPayload] UpdateProductDto $productDto,
        ObjectMapperInterface $mapper,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $mapper->map($productDto, $product);

        $entityManager->persist($product);
        $entityManager->flush();

        $dto = $mapper->map($product, ProductDto::class);

        return $this->json($dto);
    }

    #[Route('/{product}', name: 'delete', methods: 'DELETE')]
    #[IsGranted('admin')]
    public function delete(
        Product $product,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->json(null, status: JsonResponse::HTTP_NO_CONTENT);
    }
}
