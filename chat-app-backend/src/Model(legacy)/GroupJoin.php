<?php

namespace App\Model;

class GroupJoin{
  private $db;

  public function __construct($db)
  {
   $this->db = $db;
  }

  public function joinGroup($groupId, $userId){
    $this->db->insert('group_members', ['group_id' => $groupId, 'user_id' => $userId]);

    return
     [
        'success' => true,
        'group_id' => $groupId,
        'user_id' => $userId
      ];

  }

  public function findMembership($groupId, $userId){
    return $this->db->get('group_members', '*',[
      'group_id' => $groupId,
      'user_id' => $userId
    ]);
  }
}