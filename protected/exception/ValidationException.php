<?php

namespace org\csflu\isms\exceptions;

/**
 * Description of ValidationException
 *
 * @author britech
 */
class ValidationException extends \Exception {

    public function __construct($message = null, $code = null, $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
