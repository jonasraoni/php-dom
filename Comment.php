<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class Comment extends CharacterData {
	function Comment($data='', &$ownerDocument){
		$this->_canHaveChild = False;
		parent::Node(COMMENT_NODE, '#comment', $ownerDocument);
		parent::CharacterData($data);
	}
}