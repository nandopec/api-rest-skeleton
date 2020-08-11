<?php
namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpTooManyRequestsException extends HttpSpecializedException {
    protected $code = 429;
    protected $message = 'Too many requests.';
    protected $title = '429 Too many requests';
    protected $description = 'The user has submitted too many requests in a given period of time.';
}
?>
