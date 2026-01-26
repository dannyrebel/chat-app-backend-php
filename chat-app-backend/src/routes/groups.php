<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controller\GroupController;
use App\Model\Group;

// Route to create a chat group
$app->post('/groups', function(Request $request, Response $response){
    $db = db();

    $groupModel = new Group($db);

    $controller = new GroupController($groupModel);

    return $controller->createGroup($request, $response);
   
});
