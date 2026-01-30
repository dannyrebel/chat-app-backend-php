<?php

namespace App\Repository;

use App\Entity\Group;


class GroupRepository{
  private $entityManager;

  public function __construct($entityManager)
  {
    $this->entityManager = $entityManager;
  }

  public function create(string $name = 'Unnamed group'): Group {

  $group = new Group($name);

  $this->entityManager->persist($group);
  $this->entityManager->flush();

  return $group;
  }

  
  public function findById(int $id): ?Group
    {
        return $this->entityManager->find(Group::class, $id);
    }
}