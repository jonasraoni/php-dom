<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */
 
class CharacterData extends Node {
	var	$length=0,
		$data='';

	function CharacterData($data=''){
		$this->nodeValue = $data;
		$this->data = &$this->nodeValue;
		$this->length = StrLen($this->data);
	}

	function substringData($offset, $count=-1){
		return $count>=0 ? SubStr($this->data, $offset, $count) : SubStr($this->data, $offset);
	}

	function appendData($data){
		if(!$this->_canModify) return false;
		$this->data.=$data;
		$this->length = StrLen($this->data);
		return true;
	}

	function insertData($offset, $data){
		if(!$this->_canModify) return false;
		$this->data = $this->substringData(0,$offset).$data.$this->substringData($offset);
		$this->length = StrLen($this->data);
		return true;
	}

	function deleteData($offset, $count=-1){
		if(!$this->_canModify) return false;
		$this->data = $this->substringData(0,$offset).($count>=0 ? $this->substringData($offset+$count):'');
		$this->length = StrLen($this->data);
		return true;
	}

	function replaceData($offset, $count, $data){
		$this->deleteData($offset,$count);
		return $this->insertData($offset,$data);
	}
}