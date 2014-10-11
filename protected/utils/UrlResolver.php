<?php
namespace org\csflu\isms\util;
class UrlResolver {
	public static function resolveUrl($url = []){
		if(count($url) > 1){
			$params="";
			foreach($url as $paramName => $paramValue){
				if(!is_numeric($paramName)){
					$params.='&'.$paramName.'='.$paramValue;
				}
			}
			return '?r='.$url[0].$params;
		} else{
			return '?r='.$url[0];
		}
	}
}