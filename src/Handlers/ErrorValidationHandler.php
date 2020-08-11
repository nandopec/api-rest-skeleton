<?php
namespace App\Handlers;

use Selective\Validation\Transformer\TransformerInterface;
use Selective\Validation\ValidationResult;
use Selective\Validation\ValidationError;

class ErrorValidationHandler implements TransformerInterface {
    /**
     * Transform the given ValidationResult into an array.
     *
     * @param ValidationResult $validationResult The validation result
     *
     * @return array<mixed> The transformed result
     */
    public function transform(ValidationResult $validationResult ): array {
        $errors = $validationResult->getErrors();
        if ($errors) {
            $errorDetails = $this->getErrorDetails($errors);
        }
        $error = [];
        $error['statusCode'] = 422;
        $error['error'] = [
            'type' => 'UNPROCESSABLE_ENTITY',
            'message' => $errorDetails
        ];
        return $error;
    }

    /**
     * Get error details.
     *
     * @param ValidationError[] $errors The errors
     *
     * @return array<mixed> The details as array
     */
    private function getErrorDetails(array $errors): array {
        $details = [];
        foreach ($errors as $error) {
            $item['message'] = $error->getMessage();
            $fieldName = $error->getField();
            if ($fieldName !== null) {
                $item['field'] = $fieldName;
            }
            $details[] = $item;
        }
        return $details;
    }
}
?>
