<?php

namespace App\Dto\User\In;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(User::class)]
#[UniqueEntity(
    fields: ['email'],
    entityClass: User::class,
)]
final class AccountDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $username,

        #[Assert\NotBlank]
        public string $firstname,

        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\PasswordStrength]
        #[Map(if: false)]
        public string $password,
    )
    {
    }
}