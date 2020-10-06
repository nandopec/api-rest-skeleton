<?php
namespace App\Exceptions;

use Exception;

class ForbiddenException extends Exception {
    protected $code = 403;
    protected $message = '';

    public function __construct($message = '') {
        $this->message = $message;
    }
}
?>
