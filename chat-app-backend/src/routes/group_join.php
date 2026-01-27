<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controller\GroupJoinController;
use App\Model\GroupJoin;

$app->post('/groups/{id}/join', function(Request $request, Response $response, $args){
  $db = db();

  $groupJoinModel = new GroupJoin($db);
  $controller = new GroupJoinController($groupJoinModel);

  return $controller->joinGroup($request, $response, $args);
});