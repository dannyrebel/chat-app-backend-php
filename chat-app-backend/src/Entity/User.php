<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User{

  #[ORM\Id]
  #[ORM\Column(type: 'string', length: 255)]
  private string $id;
  
  public function __construct()
  {
    $this->id = uniqid('user_');
  }

  public function getId(): string{
    return $this->id;
  }

  public function steId(string $id): void{
    $this->id = $id;
  }

}