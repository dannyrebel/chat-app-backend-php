<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repository\UserRepository;
use Throwable;

class UserController{
  private $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function createUser(Request $request, Response $response){

    try{
      $user = $this->userRepository->create();
      $userId = $user->getId();
      
      $_SESSION['user_id'] = $userId;

      $data = [
        'success' => true,
        'id' => $userId
      ];

      $response->getBody()->write(json_encode($data));
      return $response->withHeader('Content-Type', 'application/json');
    }
    catch(Throwable $e){
      $data = [
        'success' => false,
        'error' => $e->getMessage()
      ];

            $response->getBody()->write(json_encode($data));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
  }
}