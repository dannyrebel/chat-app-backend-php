<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'group_members')]
class GroupMember{
  #[ORM\Id]
  #[ORM\ManyToOne(targetEntity: Group::class)]
  #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
  private Group $group;

  #[ORM\Id]
  #[ORM\ManyToOne(targetEntity: User::class)]
  #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
  private User $user;

  public function __construct($group, $user)
  {
    $this->group = $group;
    $this->user = $user;
  }

  public function getGroup(): Group {
    return $this->group;
  }

  public function getUser(): User{
    return $this->user;
  }
}