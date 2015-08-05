<?php

namespace org\csflu\isms\core;

use org\csflu\isms\exceptions\ApplicationException;

/**
 *
 * @author britech
 */
class ApplicationEnvironment {

	public static function initialize($location) {
		$fileName = dirname(__DIR__) . "/config/{$location}.json";
		
		if(!file_exists($fileName)){
			throw new ApplicationException("Configuration file not found");
		}

		$jsonStringData = file_get_contents($fileName);

		if($jsonStringData == false){
			throw new ApplicationException("Cannot load configuration file");
		}

		$configurationMap = json_decode($jsonStringData, true);

		if(!is_array($configurationMap) || $configurationMap == null){
			throw new ApplicationException("Cannot parse configuration file");
		}

		foreach($configurationMap as $key => $value){
			if(is_array($value)){
				foreach($value as $name => $data){
					define(strtoupper("{$key}_{$name}"), $data);
				}
			} else {
				define(strtoupper($key), $value);
			}
		}
	}

}