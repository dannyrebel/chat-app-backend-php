<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controller\GroupMemberController;
use App\Repository\GroupMemberRepository;

$app->post('/groups/{id}/join', function(Request $request, Response $response, $args){
  $em = em();

  $groupMemberRepository = new GroupMemberRepository($em);
  $controller = new GroupMemberController($groupMemberRepository);

  return $controller->joinGroup($request, $response, $args);
});