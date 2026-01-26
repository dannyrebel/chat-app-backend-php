<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controller\UserController;
use App\Model\User;

// Route to create a user
$app->post('/users', function(Request $request, Response $response){
  $db = db();

  $userModel = new User($db);

  $controller = new UserController($userModel);

  return $controller->createUser($request, $response);
});
