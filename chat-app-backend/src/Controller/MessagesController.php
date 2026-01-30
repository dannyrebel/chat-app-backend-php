<?php

namespace App\Controller;

use App\Model\GroupJoin;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repository\MessageRepository;
use App\Repository\GroupMemberRepository;
use Throwable;

class MessagesController{
  private $messageRepository;
  private $groupMemberRepository;

  public function __construct(MessageRepository $messageRepository, GroupMemberRepository $groupMemberRepository){
    $this->messageRepository = $messageRepository;
    $this->groupMemberRepository = $groupMemberRepository;
  }

  public function createMessage(Request $request, Response $response, $args){
    $groupId = (int)$args['id'];
    $userId = $request->getAttribute('user_id');

    // Validation: Check if user is logged in
        if (!$userId) {
            $data = [
                'success' => false,
                'error' => 'No current user in session. Create a user first.'
            ];
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

     try{

     // Validation: Check if user is a member of the group
      $existing = $this->groupMemberRepository->findMembership($groupId, $userId);
      
      if (!$existing) {
          $data = [
              'success' => false,
              'error' => 'You must join the group before sending messages'
          ];
          $response->getBody()->write(json_encode($data));
          return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
            }

        $body = $request->getParsedBody();
        $message = $body['message'] ?? null;

        // Validation: Check if message is not empty
        if (!$message) {
              $data = [
                'success' => false,
                'error' => 'Message cannot be empty'
            ];
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }

        // Create the message
        $message = $this->messageRepository->create($groupId, $userId, $message);

        $data = [
                'success' => true,
                'group_id' => $message->getGroup()->getId(),
                'user_id' => $message->getUser()->getId(),
                'message' => $message->getContent()
            ];
            
          $response->getBody()->write(json_encode($data));
          return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
     }   catch (\Throwable $e) {
            $data = [
                'success' => false,
                'error' => $e->getMessage()
            ];
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
  }

  public function getMessages(Request $request, Response $response, $args){
    $groupId = (int)$args['id'];

    try{
      $messages = $this->messageRepository->findByGroupId($groupId);

      $messagesData = array_map(function($message) {
          return [
              'id' => $message->getId(),
              'group_id' => $message->getGroup()->getId(),
              'user_id' => $message->getUser()->getId(),
              'content' => $message->getContent()
          ];
      }, $messages);

      $data = [
                'success' => true,
                'messages' => $messagesData
            ];
            
            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
    } catch(Throwable $e){
      $data = [
                'success' => false,
                'error' => $e->getMessage()
            ];
          $response->getBody()->write(json_encode($data));
          return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
  }
}