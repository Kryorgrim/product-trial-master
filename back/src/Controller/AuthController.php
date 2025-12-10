<?php

namespace App\Controller;

use App\Dto\User\In\AccountDto;
use App\Dto\User\In\TokenDto;
use App\Dto\User\Out\UserDto;
use App\Entity\ShoppingCart;
use App\Entity\User;
use App\Entity\WishList;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

final class AuthController extends AbstractController
{
    #[Route('/account', name: 'app_account', methods: 'POST')]
    public function account(
        #[MapRequestPayload]
        AccountDto $accountDto,
        ObjectMapperInterface $mapper,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $user = $mapper->map($accountDto, User::class);

        $hashed = $passwordHasher->hashPassword(
            $user,
            $accountDto->password,
        );

        $user->setPassword($hashed);

        $entityManager->getConnection()->beginTransaction();

        try {
            $entityManager->persist($user);
            $entityManager->flush();

            $shoppingCart = new ShoppingCart();
            $shoppingCart->user = $user;
            $entityManager->persist($shoppingCart);
            $entityManager->flush();

            $wishList = new WishList();
            $wishList->user = $user;
            $entityManager->persist($wishList);
            $entityManager->flush();

            $entityManager->getConnection()->commit();
        } catch (Throwable $err) {
            $entityManager->getConnection()->rollBack();
            throw $err;
        }

        $res = $mapper->map($user, UserDto::class);

        return $this->json($res, JsonResponse::HTTP_CREATED);
    }

    #[Route('/token', name: 'app_token', methods: 'POST')]
    public function token(
        #[MapRequestPayload]
        TokenDto $tokenDto,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
    ) {
        $user = $entityManager->getRepository(User::class)
            ->findOneByEmail($tokenDto->email);

        if (!$user) {
            throw new AuthenticationException();
        }

        if (!$passwordHasher->isPasswordValid($user, $tokenDto->password)) {
            throw new AuthenticationException();
        }

        $token = JWT::encode(
            ['email' => $user->getEmail()],
            $this->getParameter('app.secret'),
            'HS256',
        );

        return $this->json([
            'token' => $token,
        ]);
    }
}
