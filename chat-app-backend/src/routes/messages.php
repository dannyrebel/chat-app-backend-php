<?php

use App\Model\GroupJoin;
use App\Model\Messages;
use App\Controller\MessagesController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/groups/{id}/messages', function(Request $request, Response $response, $args){
  $db = db();

  $messageModel = new Messages($db);
  $groupJoinModel = new GroupJoin($db);
  $controller = new MessagesController($messageModel, $groupJoinModel);

  return $controller->createMessage($request, $response, $args);
  });


$app->get('/groups/{id}/messages', function(Request $request, Response $response, $args){
    $db = db();
    
    $messageModel = new Messages($db);
    $groupMemberModel = new GroupJoin($db);
    $controller = new MessagesController($messageModel, $groupMemberModel);
    
    return $controller->getMessages($request, $response, $args);
});