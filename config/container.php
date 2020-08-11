<?php
use Slim\App;
use Psr\Container\ContainerInterface;
use Slim\Factory\AppFactory;
use App\Factories\LoggerFactory;
use Psr\Http\Message\ResponseFactoryInterface;

return [
    App::class => function(ContainerInterface $container) {
        AppFactory::setContainer($container);
        return AppFactory::create();
    },
    'settings' => function() {
        return require 'settings.php';
    },
    LoggerFactory::class => function(ContainerInterface $container) {
        return new LoggerFactory($container->get('settings')['logger']);
    },
    ResponseFactoryInterface:: class => function(ContainerInterface $container) {
        return $container->get(App::class)->getResponseFactory();
    }
]
?>
