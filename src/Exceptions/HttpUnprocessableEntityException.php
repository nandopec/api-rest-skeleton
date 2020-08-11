<?php
namespace App\Exceptions;

use Slim\Exception\HttpSpecializedException;

class HttpUnprocessableEntityException extends HttpSpecializedException {
    protected $code = 422;
    protected $message = 'Unprocessable entity.';
    protected $title = '422 Unprocessable entity';
    protected $description = 'The request was well formed but could not be followed due to semantic errors.';
}
?>
