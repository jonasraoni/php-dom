<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

//This should be implemented by the user as he wants...
class DOMErrorHandler {
	function handleError($error) {
		if($error->severity == SEVERITY_FATAL_ERROR) return false;
		return true;
	}
}