<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class DOMLocator {
	var	$lineNumber=-1,
		$columnNumber=-1,
		$offset=-1,
		$relatedNode=null,
		$uri=null;

	function DOMLocator(&$node, $lineNumber=-1, $columnNumber=-1, $uri=null, $offset=-1) {
		$this->relatedNode = &$node;
		$this->lineNumber=$lineNumber;
		$this->columnNumber=$columnNumber;
		$this->offset=$offset;
		$this->uri=$uri;
	}
}