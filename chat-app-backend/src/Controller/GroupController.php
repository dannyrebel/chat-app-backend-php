<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\Group;
use Throwable;

class GroupController{
  private $groupModel;

  public function __construct(Group $groupModel)
  {
    $this->groupModel = $groupModel;
  }

  public function createGroup(Request $request, Response $response){
    $body = $request->getParsedBody();
    $name = $body['name'] ?? 'Unnamed group';

    try{
      $group = $this->groupModel->create($name);

      $data = [
        'success' => true,
        'id' => $group['id'],
        'name' => $group['name']
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