<?php
namespace App\Actions\Index;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use App\Actions\Action;
/*use App\Exceptions\NotFoundException;
use App\Factories\LoggerFactory;
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

        // Exception use
        //throw new NotFoundException(USER_NOT_FOUND);

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
