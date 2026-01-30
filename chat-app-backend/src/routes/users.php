<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controller\UserController;
use App\Repository\UserRepository;

// Route to create a user
$app->post('/users', function(Request $request, Response $response){
  $em = em();

  $userRepository = new UserRepository($em);

  $controller = new UserController($userRepository);

  return $controller->createUser($request, $response);
});
