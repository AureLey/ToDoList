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

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private \DateTime $createdAt;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Vous devez saisir un titre.')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Vous devez saisir du contenu.')]
    private ?string $content = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isDone = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true)]
    private ?user $user = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->isDone = false;
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
     * getCreatedAt.
     *
     * @return DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * setCreatedAt.
     *
     * @param Datetime $createdAt
     */
    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * getTitle.
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * setTitle.
     *
     * @param string $title
     */
    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * getContent.
     *
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * setContent.
     *
     * @param string $content
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * isDone.
     *
     * @return bool
     */
    public function isDone(): ?bool
    {
        return $this->isDone;
    }

    public function toggle($flag): self
    {
        $this->isDone = $flag;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }
}
