<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controller\GroupController;
use App\Repository\GroupRepository;

// Route to create a chat group
$app->post('/groups', function(Request $request, Response $response){
    $em = em();

    $groupRepository = new GroupRepository($em);

    $controller = new GroupController($groupRepository);

    return $controller->createGroup($request, $response);
   
});
