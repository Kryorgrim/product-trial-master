<?php

namespace App\Dto\User\Out;

use App\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\ObjectMapper\Transform\MapCollection;

#[Map(source: User::class)]
class UserDto
{
    public int $id;

    public string $email;

    public string $username;

    public string $firstname;
}
