<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class NamedNodeMap {
	var	$length = 0,
		$_nameList= array(),
		$_indexList=array();

	function &getNamedItem($name){ return $this->_get($name); }
	function &getNamedItemNS($namespaceURI, $localName){ return $this->_get($namespaceURI.$localName); }

	function &setNamedItem(&$node){ return $this->_set($node->nodeName,$node); }
	function &setNamedItemNS(&$node){ return $this->_set($node->namespaceURI.$node->localName, $node); }

	function &removeNamedItem($name){ return $this->_del($name); }
	function &removeNamedItemNS($namespaceURI, $localName){ return $this->_del($namespaceURI.$localName); }

	function &item($index){
		if(IsSet($this->_indexList[$index])) return $this->_indexList[$index];
		return null;
	}

	function &_get($name){
		if(IsSet($this->_nameList[$name])) return $this->_nameList[$name];
		return null;
	}

	function &_set($name, &$node){
		if(!inheritsFrom($node,'Node')) return null;
		$exists = &$this->_del($name);
		$this->_nameList[$name] = &$node;
		$this->_indexList[$this->length++] = &$node;
		if($exists) return $exists;
		return null;
	}

	function &_del($name){
		if(!$found=&$this->_get($name)) return null;
		Unset($this->_nameList[$name]);
		Array_Splice($this->_indexList, $this->_getKey($found),1);
		$this->length--;
		return $found;
	}

	function _getKey(&$item){
		ForEach($this->_indexList As $key=>$node)
			If($node->_GUID == $item->_GUID)
				return $key;
		return -1;
	}
}