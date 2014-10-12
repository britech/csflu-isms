<?php
namespace org\csflu\isms\exceptions;

class DataAccessException extends \Exception{
	public function __construct($message, $code=0, $previous=null){
		parent::__construct($message, $code, $previous);
	}
}