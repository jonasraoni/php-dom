<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class DOMConfiguration {
	var $_params = array();
	function canSetParameter($name, $value) { return IsSet($this->_params[$name]); }
	function setParameter($name, $value) { $this->_params[$name] = $value; }
	function getParameter($name){ if (IsSet($this->_params[$name])) return $this->_params[$name]; return null; }
	function _setDefParams(&$params){ $this->_params = &$params; }
}

$Params = array(
	'error-handler'=>true,
	'schema-type'=>'http://www.w3.org/TR/REC-xml',
	'schema-location'=>'',
	'canonical-form'=>false,
	'cdata-sections'=>true,
	'comments'=>true,
	'datatype-normalization'=>false,
	'discard-default-content'=>true,
	'entities'=>false,
	'infoset'=>false,
	'namespaces'=>true,
	'namespace-declarations'=>true,
	'normalize-characters'=>false,
	'split-cdata-sections'=>true,
	'validate'=>false,
	'validade-if-schema'=>false,
	'whitespace-in-element-content'=>true
);