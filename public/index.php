<?php

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Create app
$app = AppFactory::create();

// Add error middleware
$errorMiddleware = $app->addErrorMiddleware(
    $_ENV['APP_DEBUG'] === 'true',
    true,
    true
);

// Add routing middleware
$app->addRoutingMiddleware();

// Add body parsing middleware
$app->addBodyParsingMiddleware();

// Load routes
$routes = require __DIR__ . '/../config/routes.php';
$routes($app);

// Serve frontend for non-API routes
$app->get('/{routes:.*}', function ($request, $response) {
    $file = __DIR__ . '/index.html';
    if (file_exists($file)) {
        $response->getBody()->write(file_get_contents($file));
        return $response->withHeader('Content-Type', 'text/html');
    }
    throw new HttpNotFoundException($request);
});

$app->run();
