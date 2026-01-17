<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/groups/{id}/messages', function(Request $request, Response $response, $args){
  $db = db();
  $groupId = $args['id'];
  $userId = $_SESSION['user_id'] ?? null;

  if (!$userId) {
      $response->getBody()->write(json_encode([
            'success' => false,
            'error' => 'No current user in session. Create a user first.'
        ]));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
  try{
    $existing = $db->get('group_members', '*', ['group_id' => $groupId, 'user_id' => $userId]);

    if (!$existing){
      $response->getBody()->write(json_encode([
        'success' => false,
        'error' => 'You must join the group before sending messages'
      ]));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $body = $request->getParsedBody();
    $message = $body['message'] ?? null;

    if (!$message){
      $response->getBody()->write(json_encode([
        'success' => false,
        'error' => 'Message cannot be empty'
      ]));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $db->insert('messages', [
      'group_id' => $groupId,
      'user_id' => $userId,
      'content' => $message
    ]);

    $response->getBody()->write(json_encode([
      'success' => true,
      'group_id' => $groupId,
      'user_id' => $userId,
      'message' => $message
    ]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
  } catch (Throwable $e){
      $response->getBody()->write(json_encode([
              'success' => false,
              'error' => $e->getMessage()
          ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
  }
  });


$app->get('/groups/{id}/messages', function(Request $request, Response $response, $args){
    $db = db();
    $groupId = $args['id'];

    try {
        $messages = $db->select('messages', '*', [
            'group_id' => $groupId,
            'ORDER' => ['created_at' => 'ASC']
        ]);

        $response->getBody()->write(json_encode([
            'success' => true,
            'messages' => $messages
        ]));
        return $response->withHeader('Content-Type', 'application/json');

    } catch (Throwable $e) {
        $response->getBody()->write(json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});