<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class AdminVoter extends Voter
{
    const ADMIN = 'admin';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute != self::ADMIN) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            $vote?->addReason('The user is not logged in.');
            return false;
        }

        if ($user->getEmail() !== 'admin@admin.com') {
            $vote?->addReason('User must be an admin.');
            return false;
        }

        return true;
    }
}