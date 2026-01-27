<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\GroupJoin;
use Throwable;

class GroupJoinController{
  private $groupJoinModel;

  public function __construct(GroupJoin $groupJoinModel)
  {
    $this->groupJoinModel = $groupJoinModel;
  }

  public function joinGroup(Request $request, Response $response, $args){
    $groupId = $args['id'];
    $userId = $_SESSION['user_id'] ?? null;

    // Validate if user is logged
    if(!$userId){
      $data = [
                'success' => false,
                'error' => 'No current user in session. Create a user first.'
            ];
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);

    }

    // Validate if we have a group
    if (!$groupId) {
          $data = [
              'success' => false,
              'error' => 'Missing group_id'
             ];
          $response->getBody()->write(json_encode($data));
          return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

      try{
        $existing = $this->groupJoinModel->findMembership($groupId, $userId);

        // Validate if user is already in the group
        if($existing){
           $data = [
                    'success' => false,
                    'error' => 'User is already in the group'
                ];
                $response->getBody()->write(json_encode($data));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // join group
        $this->groupJoinModel->joinGroup($groupId, $userId);

        $data = [
                'success' => true,
                'group_id' => $groupId,
                'user_id' => $userId
            ];

          $response->getBody()->write(json_encode($data));
          return $response->withHeader('Content-Type', 'application/json');
      } catch (Throwable $e){
        $data = [
              'success' => false,
              'error' => $e->getMessage()
            ];
          $response->getBody()->write(json_encode($data));
          return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
      }
  }

}