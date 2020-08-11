<?php
namespace App\Actions\Index;

use App\Actions\Action;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
/*use App\Factories\LoggerFactory;
use Selective\Validation\ValidationResult;
use Selective\Validation\Exception\ValidationException;*/

class IndexAction extends Action {
    //private $_loggerFactory;

    /*public function __construct(LoggerFactory $loggerFactory) {
        $this->_loggerFactory = $loggerFactory->addFileHandler('index.log')->createInstance('index');
    }*/

    protected function action(): ResponseInterface {
        $responseBody = "Hello world!";
        return $this->respondWithData($responseBody);

        // Logger use:
        /*$this->_loggerFactory->info("Hello logger");*/

        // Validation use:
        /*$validation = new ValidationResult();
        $validation->addError('email', 'Input required');
        if ($validation->isFailed()) {
            throw new ValidationException($validation);
        }*/
    }
}
?>
