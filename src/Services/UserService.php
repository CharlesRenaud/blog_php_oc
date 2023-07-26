<?php

<<<<<<< HEAD
declare(strict_types=1);

=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
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
<<<<<<< HEAD
     *
     * @throws \InvalidArgumentException
     *
     * @return User
=======
     * @return User
     * @throws \InvalidArgumentException
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
     */
    public function createUser(array $data): User
    {
        $user = new User();
        $user->setName(strip_tags($data['name']));
<<<<<<< HEAD

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Adresse e-mail invalide.');
=======
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Adresse e-mail invalide.");
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
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
<<<<<<< HEAD
     *
=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
     * @return bool
     */
    public function checkEmailExists(string $email): bool
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
<<<<<<< HEAD

=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
        return !empty($user);
    }

    /**
<<<<<<< HEAD
     * @param int $userId
     *
     * @return null|User
     */
    public function getUser(int $userId): ?User
    {
        return $this->entityManager->find(User::class, $userId);
    }

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function isAdmin(int $userId): bool
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if ($user) {
            return $user->getIsAdmin();
        }

=======
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
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
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
