<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdminAccessVoter extends Voter
{
    private const ADMIN_ACCESS = 'ADMIN_ACCESS';
    private const ADMIN_EMAIL = 'admin@admin.com';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::ADMIN_ACCESS;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($attribute === self::ADMIN_ACCESS) {
            return $this->isAdmin($user);
        }

        return false;
    }

    private function isAdmin(User $user): bool
    {
        return $user->getEmail() === self::ADMIN_EMAIL;
    }
}
