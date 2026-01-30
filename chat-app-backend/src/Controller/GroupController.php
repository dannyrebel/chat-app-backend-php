<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repository\Group;
use App\Repository\GroupRepository;
use Throwable;

class GroupController{
  private $groupRepository;

  public function __construct(GroupRepository $groupRepository)
  {
    $this->groupRepository = $groupRepository;
  }

  public function createGroup(Request $request, Response $response){
    $body = $request->getParsedBody();
    $name = $body['name'] ?? 'Unnamed group';

    try{
      $group = $this->groupRepository->create($name);

      $data = [
        'success' => true,
        'id' => $group->getId(),
        'name' => $group->getName()
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