<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class DocumentType extends Node {
	var	$name=null,
		$entities=null,
		$notations=null,
		$publicId=null,
		$systemId=null,
		$internalSubset=null;

	function DocumentType($name, &$ownerDocument, $publidId=null, $systemId=null) {
		$this->_canModify = $this->_canHaveChild = false;
		parent::Node(DOCUMENT_TYPE_NODE, $name, $ownerDocument);
		$this->_setName($name, null);
		$this->publidId = $publidId;
		$this->systemId = $systemId;
		$this->entities = &new NamedNodeMap();
		$this->notations = &new NamedNodeMap();
	}

	function &_addNotation($name, $publicId=null, $systemId=null){
		$notation = &new Notation($name, $this->ownerDocument, $publicId, $systemId);
		return $this->notations->setNamedItem($notation);
	}

	function &_getNotation($name){
		return $this->notations->getNamedItem($name);
	}

	function &_addEntity($name, $publicId=null, $systemId=null, $notationName=null){
		$entity = &new Entity($name, $this->ownerDocument, $publicId, $systemId, $notationName);
		return $this->entities->setNamedItem($notation);
	}

	function &_getEntity($name){
		return $this->entities->getNamedItem($name);
	}
}