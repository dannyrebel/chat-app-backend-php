<?php

namespace App\Model;

class Group{
  private $db;

  public function __construct($db)
  {
   $this->db = $db;
  }

  public function create($name){
    $this->db->insert('groups', [
      'name' => $name
    ]);

    $groupId = $this->db->id();

    return [
      'id' => $groupId,
      'name' => $name
    ];
  }
}