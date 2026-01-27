<?php

namespace App\Controller;

use App\Model\GroupJoin;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\Messages;
use Throwable;

class MessagesController{
  private $messagesModel;
  private $groupJoinModel;

  public function __construct(Messages $messagesModel, GroupJoin $groupJoinModel){
    $this->messagesModel = $messagesModel;
    $this->groupJoinModel = $groupJoinModel;
  }

  public function createMessage(Request $request, Response $response, $args){
    $groupId = $args['id'];
    $userId = $_SESSION['user_id'] ?? null;

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
      $existing = $this->groupJoinModel->findMembership($groupId, $userId);
      
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
        $this->messagesModel->createMessage($groupId, $userId, $message);

        $data = [
                'success' => true,
                'group_id' => $groupId,
                'user_id' => $userId,
                'message' => $message
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
    $groupId = $args['id'];

    try{
      $messages = $this->messagesModel->getMessages($groupId);

      $data = [
                'success' => true,
                'messages' => $messages
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