<?php
session_start();

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/src/Database.php';


$app = AppFactory::create();

$app->addBodyParsingMiddleware();

// Load routes
require __DIR__ . '/../src/routes/users.php';
require __DIR__ . '/../src/routes/groups.php';
require __DIR__ . '/../src/routes/group_join.php';
require __DIR__ . '/../src/routes/messages.php';

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('API is running');
    return $response;
});

if (!debug_backtrace()) {
    $app->run();
} else {
    return $app;
}