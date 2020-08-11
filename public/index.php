<?php
use DI\ContainerBuilder;
use Slim\App;
date_default_timezone_set('America/Mexico_City');

require __DIR__ . '/../vendor/autoload.php';

// Config dependencies
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$container = $containerBuilder->build();

// Create Slim instance
$app = $container->get(App::class);

// Config middlewares
$middlewaresConfig = require __DIR__ . '/../config/middlewares.php';
$middlewaresConfig($app);

// Config database
$databaseConfig = require __DIR__ . '/../config/database.php';
$databaseConfig($app->getContainer()->get('settings')['db']);

// Config routes
$routesConfig = require __DIR__ . '/../config/routes.php';
$routesConfig($app);

$app->run();
?>
