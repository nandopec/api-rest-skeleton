<?php
namespace App\Handlers;

use App\Helpers\ErrorHelper;
use App\Helpers\PayloadHelper;
use Slim\Exception\HttpException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpMethodNotAllowedException;
use App\Exceptions\HttpUnprocessableEntityException;
use App\Exceptions\HttpTooManyRequestsException;
use App\Exceptions\HttpBadGatewayException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Throwable;
use Exception;

class HttpErrorHandler extends SlimErrorHandler{
    /**
     * @inheritdoc
     */
    protected function respond(): Response {
        $exception = $this->exception;
        $statusCode = 500;
        $error = new ErrorHelper(
            ErrorHelper::SERVER_ERROR,
            'An internal error has occurred while processing your request.'
        );

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $error->setMessage($exception->getMessage());

            if ($exception instanceof HttpNotFoundException) {
                $error->setType(ErrorHelper::RESOURCE_NOT_FOUND);
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $error->setType(ErrorHelper::NOT_ALLOWED);
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $error->setType(ErrorHelper::UNAUTHENTICATED);
            } elseif ($exception instanceof HttpForbiddenException) {
                $error->setType(ErrorHelper::INSUFFICIENT_PRIVILEGES);
            } elseif ($exception instanceof HttpBadRequestException) {
                $error->setType(ErrorHelper::BAD_REQUEST);
            } elseif ($exception instanceof HttpNotImplementedException) {
                $error->setType(ErrorHelper::NOT_IMPLEMENTED);
            } elseif ($exception instanceof HttpUnprocessableEntityException) {
                $error->setType(ErrorHelper::UNPROCESSABLE_ENTITY);
            } elseif ($exception instanceof HttpTooManyRequestsException) {
                $error->setType(ErrorHelper::TOO_MANY_REQUESTS);
            } elseif ($exception instanceof HttpBadRequestException) {
                $error->setType(ErrorHelper::BAD_GATEWAY);
            }
        }

        if (
            !($exception instanceof HttpException)
            && ($exception instanceof Exception || $exception instanceof Throwable)
            && $this->displayErrorDetails
        ) {
            $error->setMessage($exception->getMessage());
        }

        $payload = new PayloadHelper($statusCode, null, $error);
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>
