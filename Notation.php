<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class Notation extends Node {
	var	$publicId=null,
		$systemId=null;

	function Notation($name, &$ownerDocument, $publicId=null, $systemId=null){
		$this->_canHaveChild = $this->_canModify = false;
		parent::Node(NOTATION_NODE, $name, $ownerDocument);
		$this->publicId = $publicId;
		$this->systemId = $systemId;
	}
}