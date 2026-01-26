<?php

namespace App\Model;

class User
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function create()
    {
        $userId = uniqid('user_');
        
        $this->db->insert('users', [
            'id' => $userId
        ]);
        
        return $userId;
    }
}