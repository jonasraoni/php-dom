<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class Text extends CharacterData {

	function Text($data='', &$ownerDocument){
		$this->_canHaveChild = False;
		parent::Node(TEXT_NODE, '#text', $ownerDocument);
		parent::CharacterData($data);
	}

	function isWhitespaceInElementContent() {
         	return PReg_Match('/[\r|\n|\t|]|\s{2,}/',$this->data);
	}

	function splitText($offset) {
		$newNode = &parent::Node(TEXT_NODE, '#text', $this->ownerDocument);
		$newNode->CharacterData($this->substringData($offset));
		$this->parentNode->insertBefore($newNode, $this->nextSibling);
	}

	function &replaceWholeText($content) {
         	$Current = &$this;
		while($Current) {
			if(inheritsFrom($Current->previousSibling, 'CharacterData')) $Current=&$Current->previousSibling;
			else break;
			$Current->parentNode->removeChild($Current->nextSibling);
		}
                 $dataBelow = '';
		while($Current) {
			if(inheritsFrom($Current->nextSibling, 'CharacterData')) $Current=&$Current->nextSibling;
			else break;
			$Current->parentNode->removeChild($Current->previousSibling);
		}
                 $newTextNode = &$this->ownerDocument->createTextNode($content);
                 $this->parentNode->replaceChild($newTextNode, $Current);
                 return $newTextNode;
	}

	function wholeText() {
                 $dataAbove = '';
         	$Current = &$this;
		while($Current) {
			$dataAbove = $Current->data.$dataAbove;
			if(inheritsFrom($Current->previousSibling, 'CharacterData')) $Current=&$Current->previousSibling;
			else break;
		}
                 $dataBelow = '';
                 $Current=&$this;
		while($Current) {
			if(inheritsFrom($Current->nextSibling, 'CharacterData')) $Current=&$Current->nextSibling;
			else break;
			$dataBelow .= $Current->data;
		}
                 return $dataAbove.$dataBelow;
	}

	function &_mergeAdjacentTexts() {
                 $dataAbove = '';
         	$Current = &$this;
		while($Current) {
			$dataAbove = $Current->data.$dataAbove;
			if(inheritsFrom($Current->previousSibling, 'CharacterData')) $Current=&$Current->previousSibling;
			else break;
			$Current->parentNode->removeChild($Current->nextSibling);
		}
                 $dataBelow = '';
		while($Current) {
			if(inheritsFrom($Current->nextSibling, 'CharacterData')) $Current=&$Current->nextSibling;
			else break;
			$dataBelow .= $Current->data;
			$Current->parentNode->removeChild($Current->previousSibling);
		}
                 $this->data = $dataAbove.$dataBelow;
                 $this->parentNode->replaceChild($this, $Current);
                 return $this;
	}
}