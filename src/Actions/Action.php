<?php
namespace App\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\PayloadHelper;
use Exception;

abstract class Action {
    protected $args;
    protected $queryParams;
    protected $request;
    protected $requestBody;
    protected $response;
    private $_queryParams;

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

        $this->args = $args;
        $this->requestBody = ($request->getParsedBody() !== null) ? $request->getParsedBody() : [];
        $this->request = $request;
        $this->response = $response;
        $this->_queryParams = ($request->getQueryParams() !== null) ? $request->getQueryParams() : [];
        $this->queryParams = array(
            'fields' => $this->_getFields(),
            'filter' => $this->_getFilters(),
            'search' => $this->_getSearches(),
            'sortBy' => $this->_getSortsBy(),
            'page' => $this->_getPage(),
            'perPage' => $this->_getPerPage()
        );
        return $this->action();
    }

    /**
     * @return Response
     * @throws HttpBadRequestException|HttpNotFoundException|ValidationException|HttpInternalServerErrorException|HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * Add the data to the response
     * @param  array|object|null   $data Datos
     * @return Response                  Respuesta
     */
    protected function respondWithData($data = null): Response {
        $payload = new PayloadHelper($data);
        return $this->createResponse($payload);
    }

    /**
     * Create the response
     * @param  PayloadHelper $payload Datos a agregar
     * @return Response               Respuesta
     */
    protected function createResponse(PayloadHelper $payload): Response {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);
        return $this->response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Gets the requested fields
     * @return array The fields
     */
    private function _getFields(): array {
        $fields = array();
        if(!empty($this->requestQueryParams['fields'])) {
            $fields = explode(',', $this->requestQueryParams['fields']);
        }
        return $fields;
    }

    /**
     * Gets the filters to apply
     * @return array The filters
     */
    private function _getFilters(): array {
        $operators = ['>=', '<=', '!=', '=', '>', '<'];
        $queryFilters = array();
        if(!empty($this->requestQueryParams['filter'])) {
            $filters = explode(',', $this->requestQueryParams['filter']);
            foreach($filters as $filter) {
                if($this->_isValidFilter($filter)) {
                    $field = explode('[', $filter)[0];
                    $value = explode(']', $filter)[1];
                    if(!empty($value)) {
                        $startPos = strpos($filter, '[') + 1;
                        $endPos = strpos($filter, ']');
                        $operator = substr($filter, $startPos, $endPos - $startPos);
                        $queryFilters[] = array(
                            'field' => $field,
                            'value' => $value,
                            'operator' => $operator
                        );
                    }
                }
            }
        }
        return $queryFilters;
    }

    /**
     * Gets the page number to get
     * @return int The page number
     */
    private function _getPage(): int {
        return (!empty($this->requestQueryParams['page'])) ? $this->requestQueryParams['page'] : QUERY_PARAM_DEFAULT_PAGE;
    }

    /**
     * Gets the total number of items that are fetched per page
     * @return int The total number of items
     */
    private function _getPerPage(): int {
        return (!empty($this->requestQueryParams['perPage'])) ? $this->requestQueryParams['perPage'] : QUERY_PARAM_DEFAULT_PER_PAGE;
    }

    /**
     * Gets the searches to apply
     * @return array The searches
     */
    private function _getSearches(): array {
        $search = array();
        if(!empty($this->requestQueryParams['search'])) {
            $items = explode(',', $this->requestQueryParams['search']);
            foreach($items as $value) {
                $searchValues = explode(':', $value);
                if(count($searchValues) === 2) {
                    $values = explode(' ', trim($searchValues[1]));
                    $search[] = array(
                        'field' => $searchValues[0],
                        'values' => $values
                    );
                }
            }
        }
        return $search;
    }

    /**
     * Gets the orderings to apply
     * @return array The orderings
     */
    private function _getSortsBy(): array {
        $sortBy = array();
        if(!empty($this->requestQueryParams['sortBy'])) {
            $items = explode(',', $this->requestQueryParams['sortBy']);
            foreach($items as $value) {
                $field = (substr($value, 0, 1) === '-') ? substr($value, 1) : $value;
                $sortValue = (substr($value, 0, 1) === '-') ? 'desc' : 'asc';
                $sortBy[] = array(
                    'field' => $field,
                    'value' => $sortValue
                );
            }
        }
        return $sortBy;
    }

    /**
     * Valida si un filtro tiene el formato correcto
     * @param  string $input Filtro a evaluar
     * @return bool          True si el filtro es valido, false en caso contrario
     */
    private function _isValidFilter(string $input): bool {
        $operators = '';
        foreach (OPERATORS as $value) { $operators.= $value."|"; }
        $operators = substr( $operators, 0, -1 );
        $regex = "/^[a-zA-Z0-9]+\[(" . $operators . "){1}+\][a-zA-Z0-9]+$/";
        return ( \preg_match($regex, $input) ) ? true : false;
    }
}
