<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */
 
class Entity extends Node {
	var	$publicId = null,
		$systemId = null,
		$notationName = null,
		$actualEncoding = null,
		$encoding = null,
		$version = null;

	function Entity($name, &$ownerDocument, $publicId, $systemId, $notationName) {
		$this->_canHaveChild = $this->_canModify = false;
		parent::Node(ENTITY_NODE, $name, $ownerDocument);
		$this->publicId = $publicId;
		$this->systemId = $systemId;
		$this->notation = $notationName;
	}
}