<?php
session_start();

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/src/Database.php';

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/../src/Entity'], 
    isDevMode: true,  
);

$connectionParams = [
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/../storage/database.sqlite'
];

$connection = DriverManager::getConnection($connectionParams, $config);

$entityManager = new EntityManager($connection, $config);

function em() {
    global $entityManager;
    return $entityManager;
}


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