<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Route to create a chat group
$app->post('/groups', function(Request $request, Response $response){
    $db = db();

    $body = $request->getParsedBody();
    $name = $body['name'] ?? 'Unnamed group'; // default if no name is provided

    try{
      $db->insert('groups', [
        'name' => $name
      ]);

      $groupId = $db->id();

      $data = [
        'success' => true,
        'id' => $groupId,
        'name' => $name
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
});
