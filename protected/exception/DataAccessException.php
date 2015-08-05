<?php
namespace org\csflu\isms\exceptions;

class DataAccessException extends \Exception{
	public function __construct($message, $previous=null){
		parent::__construct($message, 0, $previous);
	}
}