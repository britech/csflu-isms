<?php
namespace org\csflu\isms\exceptions;

class ServiceException extends \Exception{
	public function __construct($message, $code, $previous){
		parent::__construct($message, $code, $previous);
	}
}