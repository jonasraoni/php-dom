<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class DOMStringList{
	var	$length=0,
		$_strings=array();

	function _add($String){
		$this->_strings[$this->length++] = $string;
	}

	function _item($index){
		if(IsSet($this->_strings[$index])) return $this->_strings[$index];
		return null;
	}

	function _del($index){
		if(IsSet($this->_strings[$index])) return (bool)Array_Splice($this->_strings,$index,1);
		return true;
	}

	function _indexOf($search){
		ForEach($this->_strings As $key=>$string)
			if($string == $search) return $key;
		return -1;
	}
}