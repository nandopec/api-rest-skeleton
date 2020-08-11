<?php
namespace App\Middlewares;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;
use Selective\Validation\Exception\ValidationException;
use Psr\Http\Message\ResponseFactoryInterface;
use App\Handlers\ErrorValidationHandler;
use Selective\Validation\Encoder\JsonEncoder;

final class ErrorValidationMiddleware implements MiddlewareInterface {
    private $_container;

    public function __construct(ContainerInterface $container) {
        $this->_container = $container;
    }

    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     * @return ResponseInterface The response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        try {
            return $handler->handle($request);
        } catch (ValidationException $exception) {
            $response = $this->_container->get(ResponseFactoryInterface::class)->createResponse()->withStatus(422)->withHeader('Content-Type', 'application/json');
            $transformer = new ErrorValidationHandler();
            $encoder = new JsonEncoder();
            $data = $transformer->transform($exception->getValidationResult());
            $content = $encoder->encode($data);
            $response->getBody()->write($content);
            return $response;
        }
    }
}
?>
