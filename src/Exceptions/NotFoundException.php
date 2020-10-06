<?php
namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception {
    protected $code = 404;
    protected $message = '';

    public function __construct($message = '') {
        $this->message = $message;
    }
}
?>
