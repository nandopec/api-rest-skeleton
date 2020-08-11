<?php
namespace App\Helpers;

use JsonSerializable;

class PayloadHelper implements JsonSerializable {
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array|object|null
     */
    private $data;

    /**
     * @var ErrorHelper|null
     */
    private $error;

    /**
     * @param int                   $statusCode
     * @param array|object|null     $data
     * @param ErrorHelper|null            $error
     */
    public function __construct(int $statusCode = 200, $data = null, ?ErrorHelper $error = null) {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->error = $error;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int {
        return $this->statusCode;
    }

    /**
     * @return array|null|object
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return ErrorHelper|null
     */
    public function getError(): ?ErrorHelper {
        return $this->error;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        $payload = ['statusCode' => $this->statusCode];
        if ($this->data !== null) {
            $payload['data'] = $this->data;
        } elseif ($this->error !== null) {
            $payload['error'] = $this->error;
        }
        return $payload;
    }
}
?>
