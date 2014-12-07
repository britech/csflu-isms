<?php

namespace org\csflu\isms\exceptions;

/**
 * Description of ApplicationException
 *
 * @author britech
 */
class ApplicationException extends \Exception {

    public function __construct($message, $code = null, $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
