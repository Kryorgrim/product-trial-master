<?php

namespace App\Dto\User\In;

use Symfony\Component\Validator\Constraints as Assert;

final class TokenDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $email,

        #[Assert\NotBlank]
        public string $password,
    )
    {
    }
}