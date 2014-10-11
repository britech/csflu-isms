<?php
namespace org\csflu\isms\core\exceptions;

class DataAccessException extends \Exception{
	public function __construct($message, $code, $previous=null){
		parent::__construct($message, $code, $previous);
	}
}