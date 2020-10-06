<?php
namespace App\Exceptions;

use Exception;

class UnprocessableEntityException extends Exception {
    protected $code = 422;
    protected $message = '';

    public function __construct($message = '') {
        $this->message = $message;
    }
}
?>
