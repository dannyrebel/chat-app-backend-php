<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository{
  private $entityManager;

  public function __construct($entityManager)
  {
    $this->entityManager = $entityManager;
  }

  public function create(): User{
    $user = new User();

    $this->entityManager->persist($user);
    $this->entityManager->flush();

    return $user;
  }

  public function findById(string $id): ?User
    {
        return $this->entityManager->find(User::class, $id);
    }
}