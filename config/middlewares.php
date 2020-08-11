<?php
use Slim\App;
use App\Middlewares\CorsMiddleware;
use App\Handlers\HttpErrorHandler;
use App\Middlewares\ErrorValidationMiddleware;

return function(App $app) {
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $app->add(ErrorValidationMiddleware::class);

    $displayErrors = $settings = $app->getContainer()->get('settings')['displayErrors'];
    $errorMiddleware = $app->addErrorMiddleware($displayErrors, false, false);
    $app->add(CorsMiddleware::class);

    // Add custom error handler
    if(!$displayErrors) {
        $errorHandler = new HttpErrorHandler($app->getCallableResolver(), $app->getResponseFactory());
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
    }
}
?>
