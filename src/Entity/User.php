<?php

declare(strict_types=1);

/*
 * This file is part of Todolist
 *
 * (c)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table]
#[UniqueEntity(fields: 'email', message: 'Cet Email est déjà utilisé')]
#[UniqueEntity(fields: 'username', message: 'Ce nom utilisateur est déjà utilisé')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank(message: 'Vous devez saisir un nom d\'utilisateur.')]
    private ?string $username = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $password = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank(message: 'Vous devez saisir une adresse email.')]
    #[Assert\Email(message: 'Le format de l\'adresse n\'est pas correcte.')]
    private ?string $email = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Task::class)]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

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
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * setRoles.
     *
     * @see UserInterface
     *
     * @param mixed $roles
     */
    public function setRoles(array $roles): self
    {
        if (null === $roles) {
            return $roles[] = 'ROLE_USER';
        }

        $this->roles = $roles;

        return $this;
    }

    /**
     * getUserIdentifier.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * Returning a salt is only needed if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     */
    /**
     * getSalt.
     *
     * @see UserInterface
     *
     * @return string
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * eraseCredentials.
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * addTask.
     *
     * @param mixed $task
     */
    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setUser($this);
        }

        return $this;
    }

    /**
     * removeTask.
     *
     * @param mixed $task
     */
    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }
}
