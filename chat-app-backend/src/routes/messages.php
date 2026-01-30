<?php

use App\Model\GroupJoin;
use App\Model\Messages;
use App\Controller\MessagesController;
use App\Middleware\JwtAuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repository\MessageRepository;
use App\Repository\GroupMemberRepository;
use App\Service\JwtService;

$app->post('/groups/{id}/messages', function(Request $request, Response $response, $args){
  $em = em();

  $messageRepository = new MessageRepository($em);
  $groupMemberRepository = new GroupMemberRepository($em);
  $controller = new MessagesController($messageRepository, $groupMemberRepository);

  return $controller->createMessage($request, $response, $args);
  })->add(new JwtAuthMiddleware(new JwtService()));


$app->get('/groups/{id}/messages', function(Request $request, Response $response, $args){
    $em = em();
    
   $messageRepository = new MessageRepository($em);
    $groupMemberRepository = new GroupMemberRepository($em);
    $controller = new MessagesController($messageRepository, $groupMemberRepository);
    
    return $controller->getMessages($request, $response, $args);
});