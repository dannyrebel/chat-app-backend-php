<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'messages')]
class Message{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private int $id;

  #[ORM\ManyToOne(targetEntity: Group::class)]
  #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id', nullable: false)]
  private Group $group;

  #[ORM\ManyToOne(targetEntity: User::class)]
  #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
  private User $user;

  #[ORM\Column(type: 'text')]
  private string $content;

  #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
  private \DateTimeImmutable $createdAt;

  public function __construct(Group $group, User $user, string $content)
  {
    $this->group = $group;
    $this->user = $user;
    $this->content = $content;
  }

    public function getId(): int
    {
        return $this->id;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getContent(): string
    {
        return $this->content;
    }

      public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}