<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class DOMImplementationSource{
	function &getDOMImplementation($features){
		Global $DOMImplementationList;
		PReg_Match_All('/([A-Z| ]*) ([0-9|\.]{1,})/', $features, $features);
		ForEach($DOMImplementationList->_implementationList As $implementation) {
			$ok = true;
			For($i=0; $i<SizeOf($features[1]); $i++)
				if(!$implementation->hasFeature(Trim($features[1][$i]), Trim($features[2][$i]))) {
					$ok=false; break;
				}
			if($ok) return $implementation;
		}
		return null;
         }

	function &getDOMImplementations($features){
		Global $DOMImplementationList;
		$matchedImplementations = &new DOMImplementationList;
		PReg_Match_All('/([A-Z| ]*) ([0-9|\.]{1,})/', $features, $features);
		ForEach($DOMImplementationList->_implementationList As $implementation) {
			$ok = true;
			For($i=0; $i<SizeOf($features[1]); $i++)
				if(!$implementation->hasFeature(Trim($features[1][$i]), Trim($features[2][$i]))) {
					$ok=false; break;
				}
			if($ok) $matchedImplementations->_add($implementation);
		}
		return $matchedImplementations;
	}
}