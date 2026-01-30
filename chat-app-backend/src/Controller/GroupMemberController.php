<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repository\GroupMemberRepository;

class GroupMemberController
{
    private $groupMemberRepository;

    public function __construct(GroupMemberRepository $groupMemberRepository)
    {
        $this->groupMemberRepository = $groupMemberRepository;
    }

    public function joinGroup(Request $request, Response $response, array $args)
    {
        $groupId = (int)$args['id'];
        
        // Get user ID from JWT (added by middleware)
        $userId = $request->getAttribute('user_id');
        
        try {
            // Check if user is already in the group
            $existing = $this->groupMemberRepository->findMembership($groupId, $userId);

            if ($existing) {
                $data = [
                    'success' => false,
                    'error' => 'User is already in the group'
                ];
                $response->getBody()->write(json_encode($data));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

            // Add user to group
            $groupMember = $this->groupMemberRepository->addMember($groupId, $userId);

            $data = [
                'success' => true,
                'group_id' => $groupMember->getGroup()->getId(),
                'user_id' => $groupMember->getUser()->getId()
            ];

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');

        } catch (\Throwable $e) {
            $data = [
                'success' => false,
                'error' => $e->getMessage()
            ];
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}