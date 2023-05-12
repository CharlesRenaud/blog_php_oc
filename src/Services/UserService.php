<?php

namespace App\Services;

use App\Entities\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $data
     * @return User
     * @throws \InvalidArgumentException
     */
    public function createUser(array $data): User
    {
        $user = new User();
        $user->setName(strip_tags($data['name']));
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Adresse e-mail invalide.");
        }
        $user->setEmail(strip_tags($data['email']));
        $user->setIsAdmin($data['isAdmin']);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function checkEmailExists(string $email): bool
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        return !empty($user);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function getUser(int $id): ?User
    {
        return $this->entityManager->find(User::class, $id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isAdmin(int $id): bool
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if ($user) {
            return $user->getIsAdmin();
        }
        return false;
    }

    /**
     * @param User $user
     */
    public function editUser(User $user): void
    {
        $user->setUpdatedAt(new \DateTime());
        $this->entityManager->flush();
    }

    /**
     * @param int $userId
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
