<?php
namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpBadGatewayException extends HttpSpecializedException {
    protected $code = 502;
    protected $message = 'Bad gateway.';
    protected $title = '502 Bad gateway';
    protected $description = 'The server got an invalid response from an external dependecy.';
}
?>
