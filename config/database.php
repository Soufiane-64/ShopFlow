<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

return function () {
    $paths = [__DIR__ . '/../src/Models'];
    $isDevMode = $_ENV['APP_ENV'] === 'development';

    $config = ORMSetup::createAttributeMetadataConfiguration(
        $paths,
        $isDevMode
    );

    $connection = DriverManager::getConnection([
        'driver' => 'pdo_mysql',
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'dbname' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'charset' => 'utf8mb4',
    ], $config);

    return new EntityManager($connection, $config);
};
