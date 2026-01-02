<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleUserProvider implements UserProviderInterface
{
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return new InMemoryUser($identifier, '', ['ROLE_USER']);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof InMemoryUser) {
            throw new UnsupportedUserException();
        }

        return new InMemoryUser($user->getUserIdentifier(), '', $user->getRoles());
    }

    public function supportsClass(string $class): bool
    {
        return InMemoryUser::class === $class;
    }
}