<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */
 
class ProcessingInstruction extends Node {
	var	$target=null,
		$data=null;

	function ProcessingInstruction($target, $data, &$ownerDocument) {
		$this->_canHaveChild = $this->_canModify = false;
		parent::Node(PROCESSING_INSTRUCTION_NODE, $target, $ownerDocument);
		$this->target = $target;
		$this->data = $data;
		$this->nodeValue = &$this->data;
	}
}