<?php

namespace org\csflu\isms\exceptions;

/**
 * Description of ValidationException
 *
 * @author britech
 */
class ControllerException extends \Exception {

    public function __construct($message, $code = null, $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
