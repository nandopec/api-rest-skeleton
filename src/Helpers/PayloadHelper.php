<?php
namespace App\Helpers;

use JsonSerializable;

class PayloadHelper implements JsonSerializable {
    /**
     * @var array|object|null
     */
    private $data;

    /**
     * @var string|array|object|null
     */
    private $error;

    /**
     * @param array|object|null         $data
     * @param string|array|object|null  $error
     */
    public function __construct($data = null, $error = null) {
        $this->data = $data;
        $this->error = $error;
    }

    /**
     * @return array|object|null
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return string|array|object|null
     */
    public function getError() {
        return $this->error;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        $payload = [];
        if ($this->data !== null) {
            $payload['data'] = $this->data;
        } elseif ($this->error !== null) {
            $payload['error'] = $this->error;
        }
        return $payload;
    }
}
?>
