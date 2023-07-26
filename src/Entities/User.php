<?php

<<<<<<< HEAD
declare(strict_types=1);

=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
<<<<<<< HEAD
 *
=======
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
<<<<<<< HEAD
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     *
=======
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="author")
     * @ORM\OneToMany(targetEntity="Post", mappedBy="author")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $email;
<<<<<<< HEAD

=======
    
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isAdmin;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

<<<<<<< HEAD
=======

>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
<<<<<<< HEAD

=======
    
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
<<<<<<< HEAD

=======
    
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 47511f0b1717e522b9c821facf56431bf8316335
