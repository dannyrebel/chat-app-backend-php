<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\Group;
use App\Entity\User;

class MessageRepository{
  private $entityManager;

  public function __construct($entityManager)
  {
    $this->entityManager = $entityManager;
  }

  public function create(int $groupId, string $userId, string $content): Message {
    $group = $this->entityManager->find(Group::class, $groupId);
    $user = $this->entityManager->find(User::class, $userId);

    if (!$group) {
        throw new \Exception("Group not found");
    }
    
    if (!$user) {
        throw new \Exception("User not found");
    }

    $message = new Message($group, $user, $content);

    $this-> entityManager->persist($message);
    $this->entityManager->flush();

    return $message;
  }

   public function findByGroupId(int $groupId): array
    {
      $group = $this->entityManager->find(Group::class, $groupId);
      
      if (!$group) {
          return [];
      }
      
      return $this->entityManager
          ->getRepository(Message::class)
          ->findBy(
              ['group' => $group],
              ['createdAt' => 'ASC']
          );
  }
}