<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */
 
class DocumentFragment extends Node {
	function DocumentFragment(&$ownerDocument){
		parent::Node(DOCUMENT_FRAGMENT_NODE, '#document-fragment', $ownerDocument);
	}
}