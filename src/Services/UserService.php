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
    public function createUser($data)
    {
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setIsAdmin($data['isAdmin']);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());
        var_dump($user->getName());
        $isAdminString = ($user->getIsAdmin() ? "admin" : "user");
        var_dump($isAdminString);
        $user->setSignature(hash_hmac('sha256', $user->getName() . $isAdminString, 'secretsecure2023randompasswordextremlyfiable'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
    public function checkEmailExists($email)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        return !empty($user);
    }

    public function getUser($id)
    {
        return $this->entityManager->find(User::class, $id);
    }
    public function isAdmin($id)
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if ($user) {
            return $user->getIsAdmin();
        }
        return false;
    }
    public function editUser(User $user)
    {
        $user->setUpdatedAt(new \DateTime());
        $this->entityManager->flush();
    }

    public function deleteUser($userId)
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
