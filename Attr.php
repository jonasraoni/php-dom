<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */
 
class Attr extends Node {
	var	$name=null,
		$specified=false,
		$value=null,
		$ownerElement=null,
		$schemaTypeInfo=null,
		$_isId=false;

	function Attr($name, &$ownerDocument, $namespaceURI=null) {
		$this->_canHaveChild = false;
		$this->nodeValue='';
		parent::Node(ATTRIBUTE_NODE, $name, $ownerDocument);
		$this->_setName($name, $namespaceURI);
	}

	function idId() {
		return $this->_isId;
	}

	function _setId($enable){
		$this->_isId=$enable;
		if($enable) $this->ownerDocument->_objectsIdList[$this->value]=&$this->ownerElement;
		elseif( IsSet( $this->ownerDocument->_objectsIdList[$this->value] ) )
			Unset($this->ownerDocument->_objectsIdList[$this->value]);
		$this->specified = true;
	}

	function _setValue($newValue) {
		$this->value = $newValue;
		$this->specified = true;
	}
}