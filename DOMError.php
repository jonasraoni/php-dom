<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class DOMError {
	var	$severity=-1,
		$message='',
		$type='',
		$relatedException=null,
		$relatedData=null,
		$location=null;

	function DOMError($severity, $message='', $location=null, $type='', $relatedData=null, $relatedException=null) {
		$this->severity=$severity;
		$this->message=$message;
		$this->type=$type;
		$this->relatedException=$relatedException;
		$this->relatedData=$relatedData;
		$this->location=$location;
	}
}