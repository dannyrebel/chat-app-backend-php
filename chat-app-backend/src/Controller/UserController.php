<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repository\UserRepository;
use Throwable;
use App\Service\JwtService;

class UserController{
  private $userRepository;
  private $jwtService;

  public function __construct(UserRepository $userRepository, JwtService $jwtService)
  {
    $this->userRepository = $userRepository;
    $this->jwtService = $jwtService;
  }

  public function createUser(Request $request, Response $response){

    try{
      $user = $this->userRepository->create();
      $userId = $user->getId();
      
      $token = $this->jwtService->generateToken($userId);

      $data = [
        'success' => true,
        'id' => $userId,
        'token' => $token
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