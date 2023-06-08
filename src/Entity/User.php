<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table("user")
 *
 * @ORM\Entity
 *
 * @UniqueEntity("email")
 */
// #[ORM\Entity]
// #[UniqueEntity(fields: 'email', message: 'Email already used', )]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // #[ORM\Id]
    // #[ORM\GeneratedValue]
    // #[ORM\Column]
    /**
     * @ORM\Column(type="integer")
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

    // #[ORM\Column(length: 255, unique: true)]
    // #[Assert\NotBlank(message: 'Vous devez saisir un nom d\'utilisateur.')]
    /**
     * @ORM\Column(type="string", length=25, unique=true)
     *
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     */
    private ?string $username = null;

    // #[ORM\Column(length: 64)]
    /**
     * @ORM\Column(type="string", length=64)
     */
    private ?string $password = null;

    // #[ORM\Column(length: 60, unique: true)]
    // #[Assert\NotBlank(message: 'Vous devez saisir une adresse email.')]
    // #[Assert\Email(message: 'Le format de l\'adresse n\'est pas correcte.')]
    /**
     * @ORM\Column(type="string", length=60, unique=true)
     *
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     *
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     */
    private ?string $email = null;

    /**
     * getId.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getUsername.
     *
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * setUsername.
     */
    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * getPassword.
     *
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * setPassword.
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * getEmail.
     *
     * @return void
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * setEmail.
     *
     * @return void
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * getRoles.
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * getUserIdentifier.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Returning a salt is only needed if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
