<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class NodeList {
	var	$length = 0,
		$_nodes = array();

	function &item($index){
		if(IsSet($this->_nodes[$index])) return $this->_nodes[$index];
		return null;
	}

	function _add(&$item){
		if(!inheritsFrom($item,'Node')) return false;
		$this->_nodes[$this->length++] = &$item;
		return true;
	}

	function &_addBefore(&$item,&$ref) {
		if(!inheritsFrom($ref, 'Node') || !inheritsFrom($item, 'Node')) return null;
		if (-1 != $key = $this->_getKey($ref)) {
			++$this->length;
			Array_Splice($this->_nodes, $key, 0, array(&$item));
			return $this->_nodes[$key];
		}
		return null;
	}

	function _getKey(&$item){
		if(!inheritsFrom($item,'Node')) return -1;
		ForEach($this->_nodes As $key=>$node)
			If($node->_GUID == $item->_GUID)
				return $key;
		return -1;

	}

	function &_remove(&$item){
		if(!inheritsFrom($item,'Node')) return null;
		if(-1 != $key = $this->_getKey($item)) {
			$this->length--;
			$removedNode = &Array_Splice($this->_nodes, $key, 1);
			return $removedNode[0];
		}
		return null;
	}

	function &_merge(&$nodeList){
		if(!inheritsFrom($nodeList,'NodeList')) return null;
		$this->_nodes=&Array_Merge($this->_nodes, $nodeList->_nodes);
		$this->length = $this->length+$nodeList->length;
	}
}