<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class DOMImplementation {
	var	$_features = array(),
		$_class = null,
		$_createDoc = null,
		$_createDocType = null;

	function DOMImplementation($createDoc, $createDocType, $features=array()){
		Global $DOMImplementationList;
		$this->_features = &$features;
		$this->_createDoc = $createDoc;
		$this->_createDocType = $createDocType;
		$DOMImplementationList->_add($this);
	}

	function hasFeature($feature, $version){
		if(IsSet($this->_features[$feature])) return IsSet($this->_features[$feature][$version]);
		return false;
	}

	function &getFeature($feature, $version){
		return hasFeature($feature, $version) ? new $this->_features[$feature][$version] : null;
	}

	function &createDocumentType($qualifiedName, $publicId=null, $systemId=null){
		$docType = &new $this->_createDocType($qualifiedName, $this, $publicId, $systemId);
		$docType->ownerDocument=null;
		return $docType;
	}

	function &createDocument($qualifiedName, $namespaceURI=null, $docType=null){
		$doc = &new $this->_createDoc;
		$doc->_setName($qualifiedName, $namespaceURI);
		$doc->docType = $docType;
		return $doc;
	}

	function _setFeature($feature, $version='1.0', $specializedObject=''){
		$this->_features[$feature][$version]=$specializedObject;
	}
}