<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controller\UserController;
use App\Repository\UserRepository;
use App\Service\JwtService;

// Route to create a user
$app->post('/users', function(Request $request, Response $response){
  $em = em();

  $userRepository = new UserRepository($em);
  $jwtService = new JwtService();

  $controller = new UserController($userRepository, $jwtService);

  return $controller->createUser($request, $response);
});
