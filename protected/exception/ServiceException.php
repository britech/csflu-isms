<?php
namespace org\csflu\isms\core\exceptions;

class ServiceException extends \Exception{
	public function __construct($message, $code, $previous){
		parent::__construct($message, $code, $previous);
	}
}