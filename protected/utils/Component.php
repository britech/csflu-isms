<?php
namespace org\csflu\isms\util;

abstract class Component {
	const PROPERTY_CLASS = "class";
	const PROPERTY_ID = "id";
	const PROPERTY_STYLE = "style";

	abstract function startComponent();
	abstract function endComponent();
	abstract function constructHeader($headerText, array $properties=null);
	abstract function renderComponent();

	public function getProperty(array $property, $propertyName){
		return array_key_exists($propertyName, $property) ? $property[$propertyName] : NULL;
	}
}

?>