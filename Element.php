<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */
 
class Element extends Node {
	var	$tagName = null,
		$schemaTypeInfo = null;

	function Element($tagName, &$ownerDocument, $namespaceURI=null){
		Global $DefAttribs;
		$this->_canHaveAttribute=true;
		parent::Node(ELEMENT_NODE, $tagName, $ownerDocument);
		$this->_setName($tagName, $namespaceURI);
		if(IsSet($DefAttribs[$this->tagName]))
			ForEach($DefAttribs[$this->tagName] As $Attr) {
				If(Eregi('(.*)=(.*)', $Attr, $Attr)) {
					$this->setAttribute(Trim($Attr[1]), Trim($Attr[2]));
					Continue;
				}
				$this->setAttribute($Attr);
			}
	}

	function hasAttribute($name){
		return $this->hasAttributes() && (bool)$this->attributes->getNamedItem(StrToLower($name));
	}

	function hasAttributeNS($namespaceURI, $localName){
		return $this->hasAttributes() && (bool)$this->attributes->getNamedItemNS($namespaceURI, $localName);
	}

	function &getElementsByTagName($tagName='*') { return $this->ownerDocument->_recursiveLoad($this, StrToUpper($tagName)); }
	function &getElementsByTagNameNS($namespaceURI='*', $localName='*'){ return $this->ownerDocument->_recursiveLoadNS($this, $namespaceURI, $localName); }

	function getAttribute($name) {
		if($attr=&$this->attributes->getNamedItem(StrToLower($name))) return $attr->value;
		return '';
	}

	function getAttributeNS($namespaceURI, $localName) {
		if($attr=&$this->attributes->getNamedItemNS($namespaceURI, $localName)) return $attr->value;
		return '';
	}

	function &getAttributeNodeNS($namespaceURI, $localName) {
		return $this->attributes->getNamedItemNS($namespaceURI, $localName);
	}

	function &getAttributeNode($name) {
		return $this->attributes->getNamedItem(StrToLower($name));
	}

	function removeAttribute($name){
		$this->attributes->removeNamedItem(StrToLower($name));
	}

	function removeAttributeNS($namespaceURI, $localName) {
		$this->attributes->removeNamedItemNS($namespaceURI, $localName);
	}

	function &removeAttributeNode(&$oldAttr){
		return $this->attributes->removeNamedItem($oldAttr->name);
	}

	function setAttribute($name, $value='') {
		$attr = &new Attr($name, $this->ownerDocument);
		$attr->ownerElement = &$this;
		$attr->_setValue($value);
		$this->attributes->setNamedItem($attr);
	}

	function setAttributeNS($namespaceURI, $qualifiedName, $value='') {
		$attr = &new Attr($qualifiedName, $this->ownerDocument, $namespaceURI);
		$attr->ownerElement = &$this;
		$attr->_setValue($value);
		$this->attributes->setNamedItemNS($attr);
	}

	function &setAttributeNode(&$newAttr) {
		$newAttr->ownerElement = &$this;
		return $this->attributes->setNamedItem($newAttr);
	}

	function &setAttributeNodeNS(&$newAttr) {
		$newAttr->ownerElement = &$this;
		return $this->attributes->setNamedItemNS($newAttr);
	}

	function setIdAttribute($name, $isId=true) {
		$attr = &new Attr($name, $this->ownerDocument);
		$attr->ownerElement = &$this;
		$attr->_setId($isId);
		$this->attributes->setNamedItem($attr);
	}

	function setIdAttributeNS($namespaceURI, $localName, $isId=true) {
		$attr = &new Attr($this->prefix.':'.$localName, $this->ownerDocument, $namespaceURI);
		$attr->ownerElement = &$this;
		$attr->_setId($isId);
		$this->attributes->setNamedItemNS($attr);
	}

	function setIdAttributeNode(&$idAttr, $isId=true) {
		$idAttr->_setId($isId);
		$idAttr->ownerElement = &$this;
		$this->attributes->setNamedItem($idAttr);
	}
}