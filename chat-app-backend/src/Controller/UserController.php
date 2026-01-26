<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\User;
use Throwable;

class UserController{
  private $userModel;

  public function __construct(User $userModel)
  {
    $this->userModel = $userModel;
  }

  public function createUser(Request $request, Response $response){

    try{
      $userId = $this->userModel->create();

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