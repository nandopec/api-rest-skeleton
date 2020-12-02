<?php
namespace App\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\PayloadHelper;
use Exception;

abstract class Action {
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $args;

    /**
     * @var array
     */
    protected $requestBody;

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args = []): Response {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $this->requestBody = ($request->getParsedBody() !== null) ? $request->getParsedBody() : [];
        return $this->action();
    }

    /**
     * @return Response
     * @throws HttpBadRequestException|HttpNotFoundException|ValidationException|HttpInternalServerErrorException|HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @param  array|object|null $data
     * @return Response
     */
    protected function respondWithData($data = null): Response {
        $payload = new PayloadHelper($data);
        return $this->respond($payload);
    }

    /**
     * @param PayloadHelper $payload
     * @return Response
     */
    protected function respond(PayloadHelper $payload): Response {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);
        return $this->response->withHeader('Content-Type', 'application/json');
    }
}
