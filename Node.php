<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

class Node{
	var	$nodeName=null,
		$nodeValue=null,
		$nodeType=null,
		$ownerDocument=null,
		$parentNode=null,
		$childNodes=null,
		$firstChild=null,
		$lastChild=null,
		$previousSibling=null,
		$nextSibling=null,
		$attributes=null,
		$namespaceURI=null,
		$prefix=null,
		$localName=null,
		$baseURI=null,
		$textContent=null,
		$_GUID=null,
		$userData=array(),
		$_canHaveChild=true,
		$_canHaveAttribute=false,
		$_canModify=true;

	function Node($nodeType, $nodeName, &$ownerDocument){
		if(!inheritsFrom($ownerDocument, 'Document')) return null;
		$this->ownerDocument = &$ownerDocument;
		$this->nodeType=$nodeType;
		$this->nodeName=$nodeName;
		$this->_GUID = getGUID();
		if($this->_canHaveChild) $this->childNodes = &new NodeList();
		if($this->_canHaveAttribute) $this->attributes = &new NamedNodeMap();
	}

	function &appendChild(&$newChild){
		if($this->_isChildLocked()) return null;
		if($newChild->nodeType==DOCUMENT_FRAGMENT_NODE) {
			For($i=0; $i<$newChild->childNodes->length; $i++)
				$this->appendChild($newChild->childNodes->item($i));
			return $node;
		}
		$this->removeChild($newChild);
		$this->childNodes->_add($newChild);
		$newChild->parentNode=&$this;
		if($this->lastChild) {
			$this->lastChild->nextSibling=&$newChild;
			$newChild->previousSibling=&$this->lastChild;
		}
		$this->lastChild=&$newChild;
		$newChild->nextSibling=null;
		$this->firstChild=&$this->childNodes->item(0);
		if($this->firstChild) $this->firstChild->previousSibling=null;
		return $newChild;

	}

	function &removeChild(&$oldChild){
		if($this->_isChildLocked() || !$this->hasChildNodes()) return null;
		if(!$this->childNodes->_remove($oldChild)) return null;
		$oldChild->_fireAllDataHandlers(NODE_REMOVED, $oldChild, $oldChild);
		if(!$oldChild->isSameNode($this->firstChild)) $oldChild->previousSibling->nextSibling=&$oldChild->nextSibling;
		if(!$oldChild->isSameNode($this->lastChild)) $oldChild->nextSibling->previousSibling=&$oldChild->previousSibling;
		$this->firstChild = &$this->childNodes->item(0);
		if($this->firstChild) $this->firstChild->previousSibling=null;
		$this->lastChild = &$this->childNodes->item($this->childNodes->length-1);
		if($this->lastChild) $this->lastChild->nextSibling=null;
		return $oldChild;
	}

	function &insertBefore(&$newChild, &$refChild){
		if($this->_isChildLocked()) return null;
		if($newChild->nodeType==DOCUMENT_FRAGMENT_NODE) {
			For($i=$newChild->childNodes->length-1; $i>=0; $i--)
				$refChild=&$this->insertBefore($newChild->childNodes->item($i), $refChild);
			return $node;
		}
		$this->removeChild($newChild);
		if($this->childNodes->_addBefore($newChild, $refChild)) {
			$newChild->parentNode = &$this;
			$newChild->nextSibling=&$refChild;
			$newChild->previousSibling=&$refChild->previousSibling;
			$newChild->previousSibling->nextSibling=&$newChild;
			$refChild->previousSibling=&$newChild;
			$this->firstChild = &$this->childNodes->item(0);
			if($this->firstChild) $this->firstChild->previousSibling=null;
			return $newChild;
		}
		return $this->appendChild($newChild);
	}

	function &replaceChild(&$newChild, &$oldChild) {
		if($this->_isChildLocked()) return null;
		$this->insertBefore($newChild, $oldChild);
		return $this->removeChild($oldChild);
	}

	function hasChildNodes(){
		return $this->_canHaveChild && (boolean)SizeOf($this->childNodes);
	}

	function hasAttributes(){
		return $this->nodeType==ELEMENT_NODE && $this->_canHaveAttribute && (boolean)SizeOf($this->attributes);
	}

	function setUserData($key, $data, $handler=null){
		$this->userData[$key]=array($data, $handler);
	}

	function getUserData($key) {
		if (IsSet($this->userData[$key])) return $this->userData[$key][0];
		return null;
	}

	function isSameNode(&$other){
		$RefCheck='ReferenceCheckup'.getGuid();
		$this->$RefCheck = $RefCheck;
		if(IsSet($other->$RefCheck))
			if($this->$RefCheck === $other->$RefCheck) {
				Unset($this->$RefCheck);
				return true;
			}
		return false;
	}

	function isEqualNode(&$other){
		return $this===$other;
	}

	function compareDocumentPosition(&$other){
		function compareSiblingsPosition(&$A,&$B){
                 	if($A->nodeType==ATTRIBUTE_NODE && $A->ownerElement->isSameNode($B)) return DOCUMENT_POSITION_IS_CONTAINED;
                 	if($B->nodeType==ATTRIBUTE_NODE && $B->ownerElement->isSameNode($A)) return DOCUMENT_POSITION_CONTAINS;
			$current = &$A;
                         while($current=&$current->nextSibling) {
				if($current->isSameNode($B)) {
					return DOCUMENT_POSITION_PRECEDING;
                                 }
                         }
                         return DOCUMENT_POSITION_FOLLOWING;
                 }

		//A must have a greater deep than B for good results.
		function compareChildrenPosition(&$A,&$B){
                         $commonNode=null;
                 	if($A->nodeType==ATTRIBUTE_NODE) $A = &$A->ownerElement;
                 	if($B->nodeType==ATTRIBUTE_NODE) $B = &$B->ownerElement;
                         $nestA=&$A; $nestB=&$B;
			while($nestB) {
				While($nestA) {
                                 	if($nestA->isSameNode($nestB)) {
                                         	$commonNode=&$nestA;
                                                 break;
                                         }
                                         $lastA=&$nestA;
					$nestA=&$nestA->parentNode;
                                 }
                                 if($commonNode) break;
                                 $nestA=&$A;
				$lastB=&$nestB;
                         	$nestB=&$nestB->parentNode;
                         }
                         if(!$commonNode) return DOCUMENT_POSITION_DISCONNECTED;
                         else return compareSiblingsPosition($lastA, $lastB);
                 }

		If($this->isSameNode($other)) return 0;
                 $deepA = $this->_getDeep();
                 $deepB = $other->_getDeep();

                 if($deepA==0 && $deepB==0)
			if($this->ownerDocument->isSameNode($other->ownerDocument)) return DOCUMENT_POSITION_IMPLEMENTATION_SPECIFIC;
                         else return DOCUMENT_POSITION_DISCONNECTED;

		if ($this->_isAncestor($other)) return DOCUMENT_POSITION_IS_CONTAINED;
		if ($other->_isAncestor($this)) return DOCUMENT_POSITION_CONTAINS;

                 if($deepA==$deepB) return compareSiblingsPosition($this,$other);
                 elseif($deepA>$deepB) return compareChildrenPosition($this, $other);
                 elseif($deepA<$deepB) return compareChildrenPosition($other, $this);
	}

	function &getFeature($feature, $version){
		return $this->ownerDocument->implementation->getFeature($feature, $version);
	}

	function isSupported($feature, $version) {
		return $this->ownerDocument->implementation->hasFeature($feature, $version);

	}

	function &cloneNode($deep=true){
         	$newNode = $this;
                 //These vars are references, so if I simple assign null to them, the original content will also be changed. So I'd had to point those to another reference or recreate them...
		if($this->_canHaveChild) {
			Unset($newNode->childNodes);
			$newNode->childNodes=&new NodeList;
		}
		if($this->_canHaveAttribute) {
			Unset($newNode->attributes);
			$newNode->attributes=&new NamedNodeMap;
		}
		Unset($newNode->nextSibling);
		Unset($newNode->lastChild);
		Unset($newNode->firstChild);
		Unset($newNode->previousSibling);
		Unset($newNode->parentNode);
		$newNode->parentNode=$newNode->previousSibling=$newNode->firstChild=$newNode->lastChild=$newNode->nextSibling=null;
                 $newNode->_fireAllDataHandlers(NODE_CLONED, $this, $newNode);
                 $newNode->userdata = array();
		Switch($this->nodeType){
			Case ATTRIBUTE_NODE:
                         	Unset($newNode->ownerElement);
                                 $newNode->ownerElement=null;
				$newNode->_setValue($newNode->value);
				return $newNode;
			Case DOCUMENT_FRAGMENT_NODE:;
			Case DOCUMENT_NODE:;
			Case ENTITY_NODE:;
			Case ENTITY_REFERENCE_NODE:;
			Case NOTATION_NODE:;
			Case PROCESSING_INSTRUCTION_NODE:;
			Case TEXT_NODE:;
			Case CDATA_SECTION_NODE:;
			Case COMMENT_NODE:
                         	return $newNode;
			Case DOCUMENT_TYPE_NODE:
                         	Unset($this->entities);
                         	Unset($this->notations);
				$this->entities = &new NamedNodeMap();
				$this->notations = &new NamedNodeMap();
				return $newNode;
			Case ELEMENT_NODE:
				For($i=0; $i<$this->attributes->length; $i++) {
					$attr = $this->attributes->item($i);
					$newNode->setAttributeNode($attr->cloneNode());
				}
				If(!$deep) return $newNode;
				For($i=0; $i<$this->childNodes->length; $i++) {
                                 	$childNode = $this->childNodes->item($i);
                                 	$newNode->appendChild($childNode->cloneNode());
                                 }
				return $newNode;
		}
		return null;
	}

	function _getDeep() {
		$current=&$this;
		if($current->nodeType==ATTRIBUTE_NODE) $current=&$current->ownerElement;
		for($deepLevel=0; $current->parentNode; $deepLevel++)
			$current=&$current->parentNode;
		return $deepLevel;
	}

	function _isAncestor(&$node){
	       	$nesting=&$this;
		while($nesting){
			if($nesting->isSameNode($node)) return true;
			$nesting=&$nesting->parentNode;
		}
		return false;
         }

	function _canModify() {
		if (!$this->parentNode && $this->_canModify) return true;
		if(!$this->parentNode->_canModify)
			return false;
		return $this->parentNode->_canModify();
	}

	function _setName($qualifiedName=null, $namespaceURI=null){
		$this->namespaceURI = $namespaceURI;
		if(Ereg('(.*):(.*)', $qualifiedName, $namespace)) {
			$this->prefix = $namespace[1];
			$this->localName = $namespace[2];
		} elseif ($this->nodeType == ELEMENT_NODE) $qualifiedName=StrToUpper($qualifiedName);
		elseif ($this->nodeType == ATTRIBUTE_NODE) $qualifiedName=StrToLower($qualifiedName);
		if ($this->nodeType == ELEMENT_NODE) $this->tagName = $qualifiedName;
		elseif ($this->nodeType == ATTRIBUTE_NODE || $this->nodeType == DOCUMENT_TYPE_NODE) $this->name = $qualifiedName;
		$this->nodeName = $qualifiedName;
	}

	function _fireAllDataHandlers($opCode=0, &$srcNode, &$dstNode){
		ForEach($this->userData As $key=>$data) $this->_callUserDataHandler($key,$opCode,&$srcNode,&$dstNode);
	}

	function _callUserDataHandler($key,$opCode=0,&$srcNode,&$dstNode){
		if (!IsSet($this->userData[$key])) return false;
		if(!$this->userData[$key][1]) return false;
		$this->userData[$key][1]($opCode, $key, &$this->userData[$key][0],&$srcNode,&$dstNode);
		return null;
	}

	function _isChildLocked(){
		return !$this->_canHaveChild || !$this->_canModify();
	}

	function textContent(){
                 $data = '';
                 if(inheritsFrom($this, 'CharacterData')) $data.=$this->data;
		if ($this->hasChildNodes()) {
                 	$current = &$this->firstChild;
			if(inheritsFrom($current, 'CharacterData')) $data.=$current->data;
                 	While($current = &$current->nextSibling)
				$data.=$current->textContent();
                 }
                 return $data;
	}

	function lookupPrefix($namespaceURI) {
		Switch ($this->nodeType) {
			case ELEMENT_NODE:
				return $this->lookupNamespacePrefix($namespaceURI, $this);
			case DOCUMENT_NODE:
				return $this->ownerDocument->documentElement->lookupNamespacePrefix($namespaceURI);
			case ENTITY_NODE :
			case NOTATION_NODE:
			case DOCUMENT_FRAGMENT_NODE:
			case DOCUMENT_TYPE_NODE:
				return null;
			case ATTRIBUTE_NODE:
				if ($this->ownerElement) return $this->ownerElement->lookupNamespacePrefix($namespaceURI);
				return null;
			default:
				if ($this->parentNode) return $this->parentNode->lookupNamespacePrefix($namespaceURI);
				return null;
		}
	}

	function lookupNamespacePrefix($namespaceURI, &$originalElement){
		if ($this->namespaceURI && $this->namespaceURI == $namespaceURI && $this->prefix && $originalElement->lookupNamespaceURI($this->prefix) == $namespaceURI)
			return $this->prefix;
		if ($this->hasAttributes())
			For($i=0; $i<$this->attributes->length; $i++){
				$attr = &$this->attributes->item($i);
				if($attr->prefix == 'xmlns' && $attr->value == $namespaceURI && $originalElement->lookupNamespaceURI($attr->localName) == $namespaceURI) return $attr->localname;
			}
		if ($this->parentNode) return $this->parentNode->lookupNamespacePrefix($namespaceURI, $originalElement);
		return null;
	}

	function lookupNamespaceURI($specifiedPrefix) {
		switch ($this->nodeType) {
			case ELEMENT_NODE:
				if ($this->namespaceURI && $this->prefix == $specifiedPrefix) return $this->namespaceURI;
				elseif ($this->hasAttributes())
                                 	For($i=0; $i<$this->attributes->length; $i++){
                                         	$attr = &$this->attributes->item($i);
						if ($attr->prefix == 'xmlns' && $attr->localName == $specifiedPrefix) return $attr->value;
						else if ($attr->localname == 'xmlns' && $speficiedPrefix == null) return $attr->value;
                                         }
				if ($this->parentNode) return $this->parentNode->lookupNamespaceURI($specifiedPrefix);
				return null;
			case DOCUMENT_NODE:
				return $this->ownerDocument->documentElement->lookupNamespaceURI($specifiedPrefix);
			case ENTITY_NODE:
			case NOTATION_NODE:
			case DOCUMENT_TYPE_NODE:
			case DOCUMENT_FRAGMENT_NODE:
				return null;
			case ATTRIBUTE_NODE:
				if ($this->ownerElement) return $this->ownerElement->lookupNamespaceURI($specifiedPrefix);
				else return null;
			default:
				if ($this->parentNode) return $this->parentNode->lookupNamespaceURI($specifiedPrefix);
				else return null;
		}
	}

	function isDefaultNamespace($specifiedNamespaceURI) {
		switch ($this->nodeType) {
			case ELEMENT_NODE:
				if (!$this->prefix) return $this->namespaceURI == $specifiedNamespaceURI;
				else if ($this->hasAttributes())
                                 	For($i=0; $i<$this->attributes->length; $i++){
                                         	$attr = &$this->attributes->item($i);
						if($attr->localName == 'xmlns') return $attr->value == $specifiedNamespaceURI;
                                         }
				if ($this->parentNode) return $this->parentNode->isDefaultNamespace($specifiedNamespaceURI);
				else return false;
			case DOCUMENT_NODE:
				return $this->ownerDocument->documentElement->isDefaultNamespace($specifiedNamespaceURI);
			case ENTITY_NODE:
			case NOTATION_NODE:
			case DOCUMENT_TYPE_NODE:
			case DOCUMENT_FRAGMENT_NODE:
				return false;
			case ATTRIBUTE_NODE:
				if ($this->ownerElement) return $this->ownerElement->isDefaultNamespace($specifiedNamespaceURI);
				else return false;
			default:
				if($this->parentNode) return $this->parentNode->isDefaultNamespace($specifiedNamespaceURI);
				else return false;
		}
	}

	function normalize(){
		/*Puts all Text [p.89] nodes in the full depth of the sub-tree underneath this Node,
		including attribute nodes, into a "normal" form where only structure (e.g., elements,
		comments, processing instructions, CDATA sections, and entity references) separates
		Text nodes, i.e., there are neither adjacent Text nodes nor empty Text nodes. This can
		be used to ensure that the DOM view of a document is the same as if it were saved and
		re-loaded, and is useful when operations (such as XPointer [XPointer] lookups) that depend
		on a particular document tree structure are to be used.
		Note: In cases where the document contains CDATASections [p.104] , the normalize
		operation alone may not be sufficient, since XPointers do not differentiate between Text
		[p.89] nodes and CDATASection [p.104] nodes.*/
	}

}