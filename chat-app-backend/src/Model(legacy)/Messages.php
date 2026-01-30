<?php

namespace App\Model;

class Messages{
  private $db;

  public function __construct($db)
  {
   $this->db = $db;
  }

  public function createMessage($groupId, $userId, $content){
    $this->db->insert('messages', [
      'group_id' => $groupId,
      'user_id' => $userId,
      'content' => $content
    ]);

    return true;
  }

  public function getMessages($groupId){
     return $this->db->select('messages', '*', [
            'group_id' => $groupId,
            'ORDER' => ['created_at' => 'ASC']
        ]);
  }
}