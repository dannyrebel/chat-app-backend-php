<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Route to create a user
$app->post('/users', function(Request $request, Response $response){
    $db = db();
    $userId = uniqid('user_');

    try{
        $db->insert('users', [
            'id' => $userId
        ]);

        $_SESSION['user_id'] = $userId;

        $data = [
            'success' => true,
            'id' => $userId
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
