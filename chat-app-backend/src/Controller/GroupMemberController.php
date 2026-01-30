<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repository\GroupMemberRepository;
use Throwable;

class GroupMemberController{
  private $groupMemberRepository;

  public function __construct(GroupMemberRepository $groupMemberRepository)
  {
    $this->groupMemberRepository = $groupMemberRepository;
  }

  public function joinGroup(Request $request, Response $response, $args){
    $groupId = (int)$args['id'];
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
        $existing = $this->groupMemberRepository->findMembership($groupId, $userId);

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
       $groupMember =  $this->groupMemberRepository->addMember($groupId, $userId);

        $data = [
                'success' => true,
                'group_id' => $groupMember->getGroup()->getId(),
                'user_id' => $groupMember->getUser()->getId()
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