<?php
namespace App\Handlers;

use Psr\Http\Message\ResponseInterface;
use Slim\Handlers\ErrorHandler;

use App\Helpers\PayloadHelper;
use App\Exceptions\ForbiddenException;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\NotFoundException;
use App\Exceptions\TooManyRequestsException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UnprocessableEntityException;

class HttpErrorHandler extends ErrorHandler {
    /**
     * @inheritdoc
     */
    protected function respond(): ResponseInterface {
        $exception = $this->exception;
        $statusCode = 500;
        $message = 'An internal error has occurred while processing your request.';
        if(
            $exception instanceof ForbiddenException ||
            $exception instanceof InternalServerErrorException ||
            $exception instanceof NotFoundException ||
            $exception instanceof TooManyRequestsException ||
            $exception instanceof UnauthorizedException ||
            $exception instanceof UnprocessableEntityException
        ) {
            $message = $exception->getMessage();
            $statusCode = $exception->getCode();
        }
        $payload = new PayloadHelper($statusCode, null, $message);
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);
        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($encodedPayload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>
