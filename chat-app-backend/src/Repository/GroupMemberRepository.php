<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\GroupMember;

class GroupMemberRepository{
  private $entityManager;

  public function __construct($entityManager){
    $this->entityManager = $entityManager;
  }

  public function findMembership(int $groupId, string $userId): ?GroupMember {
    $group = $this->entityManager->find(Group::class, $groupId);
    $user = $this->entityManager->find(User::class, $userId);
  

  if(!$group || !$user){
    return null;
  }

  return $this->entityManager->getRepository(GroupMember::class)->findOneBy(['group' => $group, 'user' => $user]);
  }

  public function addMember(int $groupId, string $userId): GroupMember{
    $group = $this->entityManager->find(Group::class, $groupId);
    $user = $this->entityManager->find(User::class, $userId);

    if (!$group) {
        throw new \Exception("Group not found");
    }
    
    if (!$user) {
        throw new \Exception("User not found");
    }

    $groupMember = new GroupMember($group, $user);

    $this->entityManager->persist($groupMember);
    $this->entityManager->flush();

    return $groupMember;
  }
}