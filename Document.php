<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */
 
class Document extends Node {
	var	$docType=null,
		$documentElement=null,
		$DOMImplementation=null,
		$actualEncoding=null,
		$encoding=null,
		$standalone=true,
		$version = null,
		//:: ERROR CHECKING NOT IMPLEMENTED YET :://
		$strictErrorChecking=false,
		$documentURI=null,
		$DOMConfiguration=null,
		$_objectsIdList=array();

	function Document() {
		Global $DOMImplementationSource, $DOMConfiguration;
		parent::Node(DOCUMENT_NODE, '#document', $this);
		$this->DOMConfiguration = &$DOMConfiguration;
		$this->implementation = &$DOMImplementationSource->getDOMImplementation('CORE 3.0');
	}

	function &createElement($tagName){ return new Element($tagName, $this); }
	function &createElementNS($namespaceURI, $qualifiedName){ return new Element($qualifiedName, $this, $namespaceURI); }
	function &createDocumentFragment(){ return new DocumentFragment($this); }
	function &createTextNode($data=''){ return new Text($data, $this); }
	function &createComment($data=''){ return new Comment($data, $this); }
	function &createCDATASection($data=''){ return new CDATASection($data, $this); }
	function &createProcessingInstruction($target, $data){ return new ProcessingInstruction($target, $data, $this); }
	function &createAttribute($name){ return new Attr($name, $this); }
	function &createAttributeNS($namespaceURI, $qualifiedName){ return new Attr($qualifiedName, $this, $namespaceURI); }
	function &createEntityReference($name) { return new EntityReference($name, $this); }
	function &getElementsByTagName($tagName='*') { return $this->_recursiveLoad($this, StrToUpper($tagName)); }
	function &getElementsByTagNameNS($namespaceURI='*', $localName='*'){ return $this->_recursiveLoadNS($this, $namespaceURI, $localName); }

	function &getElementById($elementId){
		If(IsSet($this->_objectsIdList[$elementId])) return $this->_objectsIdList[$elementId];
		return null;
	}

	function &importNode(&$importedNode, $deep=true) {
		if(!inheritsFrom($importedNode,'Node')) return null;
		$importedNode->_fireAllDataHandlers(NODE_IMPORTED, $importedNode, $importedNode);
		$importedNode->userData = array();
		Switch($importedNode->nodeType){
			Case ATTRIBUTE_NODE:
				$attr=&$this->createAttribute($importedNode->name);
				$attr->_setValue($importedNode->value);
				return $attr;
			Case DOCUMENT_FRAGMENT_NODE:
				$docFrag = &$this->createDocumentFragment();
				if(!$deep) return $docFrag;
				For($i=0; $i<$importedNode->childNodes->length; $i++) $docFrag->appendChild($this->importNode($importedNode->childNodes->item($i),$deep));
				return $docFrag;
			Case DOCUMENT_NODE:
			Case DOCUMENT_TYPE_NODE:
				return null;
			Case ELEMENT_NODE:
				if($importedNode->prefix || $importedNode->localName) $element = &$this->createElementNS($importedNode->namespaceURI, $importedNode->tagName);
				else $element = &$this->createElement($importedNode->tagName);
				For($i=0; $i<$importedNode->attributes->length; $i++) {
					$attr = &$importedNode->attributes->item($i);
					if($attr->specified) $element->setAttributeNode($this->importNode($attr, $deep));
				}
				If(!$deep) return $element;
				For($i=0; $i<$importedNode->childNodes->length; $i++) $element->appendChild($this->importNode($importedNode->childNodes->item($i),$deep));
				return $element;
			Case ENTITY_NODE:
				return new Entity($importedNode->name, $this, $importedNode->publicId, $importedNode->systemId, $importedNode->notationName);
			Case ENTITY_REFERENCE_NODE:
				return $this->createEntityReference($importedNode->name);
			Case NOTATION_NODE:
				return new Notation($importedNode->name, $this, $importedNode->publicId, $importedNode->systemId);
			Case PROCESSING_INSTRUCTION_NODE:
				return $this->createProcessingInstruction($importedNode->target, $importedNode->data);
			Case TEXT_NODE:
				return $this->createTextNode($importedNode->data);
			Case CDATA_SECTION_NODE:
				return $this->createCDATASection($importedNode->data);
			Case COMMENT_NODE:
				return $this->createComment($importedNode->data);
		}
		return null;
	}

	function &adoptNode(&$source) {
		if(!inheritsFrom($source,'Node')) return null;
		If($source->ownerDocument->implementation!=$this->implementation) return null;
		If($source->parentNode) $source->parentNode->removeChild($source);
		Switch($source->nodeType){
			Case ATTRIBUTE_NODE:
				$attr=&$this->createAttribute($source->name);
				$attr->_setValue($source->value);
				return $attr;
			Case DOCUMENT_FRAGMENT_NODE:
				$docFrag = &$this->createDocumentFragment();
				For($i=0; $i<$source->childNodes->length; $i++) $docFrag->appendChild($this->adoptNode($source->childNodes->item($i)));
				return $docFrag;
			Case DOCUMENT_NODE:
			Case ENTITY_NODE:
			Case NOTATION_NODE:
			Case DOCUMENT_TYPE_NODE:
				return null;
			Case ELEMENT_NODE:
				if($source->prefix || $source->localName) $element = &$this->createElementNS($source->namespaceURI, $source->tagName);
				else $element = &$this->createElement($source->tagName);
				For($i=0; $i<$source->attributes->length; $i++) {
					$attr = &$source->attributes->item($i);
					if($attr->specified) $element->setAttributeNode($this->adoptNode($attr));
				}
				While($source->childNodes->length>0)
					$element->appendChild($this->adoptNode($source->childNodes->item(0)));
				return $element;
			Case ENTITY_REFERENCE_NODE:
				return $this->createEntityReference($source->name);
			Case PROCESSING_INSTRUCTION_NODE:
				return $this->createProcessingInstruction($source->target, $source->data);
			Case TEXT_NODE:
				return $this->createTextNode($source->data);
			Case CDATA_SECTION_NODE:
				return $this->createCDATASection($source->data);
			Case COMMENT_NODE:
				return $this->createComment($source->data);
		}
		return null;
	}

	function normalizeDocument(){
		/*This method acts as if the document was going through a save and load
		cycle, putting the document in a "normal" form. The actual result
		depends on the features being set and governing what operations actually
		take place. See DOMConfiguration [p.96] for details. Noticeably this
		method normalizes Text [p.89] nodes, makes the document "namespace
		wellformed", according to the algorithm described in Namespace
		normalization [p.113] , by adding missing namespace declaration
		attributes and adding or changing namespace prefixes, updates the
		replacement tree of EntityReference [p.108] nodes, normalizes attribute
		values, etc. Mutation events, when supported, are generated to reflect
		the changes occuring on the document. See Namespace normalization [p.113]
		for details on how namespace declaration attributes and prefixes are
		normalized.*/
	}

	function &renameNode(&$node, $qualifiedName, $namespaceURI){
		$node->_fireAllDataHandlers(NODE_RENAMED);
		If ($node->nodeType == ATTRIBUTE_NODE) $node->_setId(False);
		$node->_setName($qualifiedName, $namespaceURI);
		return $node;
	}

	function &appendChild(&$newChild){
		if(!$this->documentElement && $this->childNodes->length==0 && $newChild->nodeType==ELEMENT_NODE) {
			$this->documentElement = &parent::appendChild($newChild);
			return $this->documentElement;
		}
		return null;
	}

	function &insertBefore() { return null; }

	function &removeChild(&$oldChild){
		if($root = parent::removeChild($oldChild)) {
			$this->documentElement=null;
			return $root;
		}
		return null;
	}

	function &replaceChild(&$newChild, &$oldChild) {
		if ($this->documentElement->isSameNode($oldChild)) {
			$oldRoot = &$this->removeChild($oldChild);
			$this->appendChild($newChild);
			return $oldRoot;
		}
		return null;
	}

	function &_recursiveLoad(&$Element, $tagName){
		$Founded = &new NodeList;
		If($Element->hasChildNodes()) {
			For($i=0; $i<$Element->childNodes->length; $i++) {
				$Child = &$Element->childNodes->item($i);
				if(!inheritsFrom($Child,'Element')) continue;
				if(($tagName=='*' && $Child->namespaceURI==null) || $Child->tagName==$tagName) $Founded->_add($Child);
				$Founded->_merge($this->_recursiveLoad(&$Child,$tagName));
			}
		}
		return $Founded;
	}

	function &_recursiveLoadNS(&$Element, $namespaceURI, $localName){
		$Founded = &new NodeList;
		If($Element->hasChildNodes()) {
			For($i=0; $i<$Element->childNodes->length; $i++) {
				$Child = &$Element->childNodes->item($i);
				if(!inheritsFrom($Child,'Element')) continue;
				if( ($namespaceURI=='*' && $localName=='*' && ($Child->localName || $Child->namespaceURI))
					|| ($namespaceURI=='*' && $Child->localName==$localName)
					|| ($Child->namespaceURI==$namespaceURI && $localName=='*')
				)
					$Founded->_add($Child);
				$Founded->_merge($this->_recursiveLoadNS(&$Child, $namespaceURI, $localName));
			}
		}
		return $Founded;
	}
}