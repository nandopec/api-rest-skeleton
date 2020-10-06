<?php
namespace App\Exceptions;

use Exception;

class InternalServerErrorException extends Exception {
    protected $code = 500;
    protected $message = '';

    public function __construct($message = '') {
        $this->message = $message;
    }
}
?>
