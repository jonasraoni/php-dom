<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

 class NameList {
	var	$length = 0,
		$_nameList= array();

	function getName($index){
		if(IsSet($this->_nameList[$index])) return $this->_nameList[$index][0];
		return null;
	}

	function getNamespaceURI($index){
		if(IsSet($this->_nameList[$index])) return $this->_nameList[$index][1];
		return null;
	}

	function _add($name=null, $namespaceURI=null){
		if(!$name && !$namespaceURI) return false;
		$this->_nameList[$this->length++] = array($name, $namespaceURI);
		return true;
	}

	function _del($index){
		if(!IsSet($this->_nameList[$index])) return false;
		Array_Splice($this->_nameList,$index,1);
		return true;
	}
}