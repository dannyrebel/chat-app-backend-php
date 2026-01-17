<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/groups/{id}/join', function(Request $request, Response $response, $args){
  $db = db();
  $groupId = $args['id'];
  $userId = $_SESSION['user_id'] ?? null;

  if (!$userId) {
      $response->getBody()->write(json_encode([
            'success' => false,
            'error' => 'No current user in session. Create a user first.'
        ]));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
  }

  try {
    
      $existing = $db->get('group_members', '*', ['group_id' => $groupId, 'user_id' => $userId]);

      if ($existing) {
        $response->getBody()->write(json_encode([
          'success' => false,
          'error' => 'User is already in the group'
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
      }

      if (!$groupId || !$userId) {
    $response->getBody()->write(json_encode([
        'success' => false,
        'error' => 'Missing group_id or user_id'
    ]));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
}

error_log("Joining group: group_id=$groupId, user_id=$userId");


      $db->insert('group_members', ['group_id' => $groupId, 'user_id' => $userId]);

      $data = [
        'success' => true,
        'group_id' => $groupId,
        'user_id' => $userId
      ];

      $response->getBody()->write(json_encode($data));
      return $response->withHeader('Content-Type', 'application/json');
  } catch (Throwable $e) {
     
      $response->getBody()->write(json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]));
      return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
  }
});