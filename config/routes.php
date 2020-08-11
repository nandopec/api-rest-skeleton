<?php
use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

return function(App $app) {
    // Define API base path
    $basePath = $app->getContainer()->get('settings')['basePath'];
    $app->setBasePath($basePath);

    // CORS
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    $app->get('/', \App\Actions\Index\IndexAction::class);

    // CORS
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });
}
?>
