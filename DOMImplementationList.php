<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class DOMImplementationList {
	var	$length = 0,
		$_implementationList = array();

	function &item($index){
		if(IsSet($this->_implementationList[$index])) return $this->_implementationList[$index];
		return null;
	}

	function _add(&$item){
		if(!inheritsFrom($item,'DOMImplementation')) return false;
		$this->_remove($item);
		$this->_implementationList[$this->length++] = &$item;
		return true;
	}

	function _getKey(&$item){
		if(!inheritsFrom($item,'DOMImplementation')) return -1;
		ForEach($this->_implementationList As $key=>$node)
			If($node == $item)
				return $key;
		return -1;
	}

	function &_remove(&$item){
		if(!inheritsFrom($item,'DOMImplementation')) return null;
		if(-1 != $key = $this->_getKey($item)) {
			$this->length--;
			$removedNode = &Array_Splice($this->_implementationList, $key, 1);
			return $removedNode[0];
		}
		return null;
         }
}