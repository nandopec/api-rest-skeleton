<?php
namespace App\Exceptions;

use Exception;

class UnauthorizedException extends Exception {
    protected $code = 401;
    protected $message = '';

    public function __construct($message = '') {
        $this->message = $message;
    }
}
?>
