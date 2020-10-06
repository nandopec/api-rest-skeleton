<?php
namespace App\Exceptions;

use Exception;

class TooManyRequestsException extends Exception {
    protected $code = 429;
    protected $message = '';

    public function __construct($message = '') {
        $this->message = $message;
    }
}
?>
