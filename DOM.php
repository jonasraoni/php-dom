<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

function inheritsFrom(&$obj, $Classname){ return Is_Object($obj) && Is_A($obj, $Classname); }
function getGUID(){ return !IsSet($GLOBALS['LASTGUID']) ? $GLOBALS['LASTGUID'] = 0 : ++$GLOBALS['LASTGUID']; }

require_once('Consts.php');
require_once('DefAttributes.php');
require_once('DOMConfiguration.php');
require_once('DOMError.php');
require_once('DOMErrorHandler.php');
require_once('DOMImplementation.php');
require_once('DOMImplementationList.php');
require_once('DOMImplementationSource.php');
require_once('DOMLocator.php');
require_once('DOMStringList.php');
require_once('NameList.php');
require_once('TypeInfo.php');

require_once('NamedNodeMap.php');
require_once('NodeList.php');
	require_once('Node.php');
         	require_once('Attr.php');
		require_once('CharacterData.php');
			require_once('Comment.php');
			require_once('Text.php');
				require_once('CDATASection.php');
		require_once('DocumentFragment.php');
		require_once('DocumentType.php');
         	require_once('Element.php');
         	require_once('Entity.php');
         	require_once('EntityReference.php');
         	require_once('Notation.php');
         	require_once('ProcessingInstruction.php');
require_once('Document.php');

$DOMConfiguration = &new DOMConfiguration;
$DOMConfiguration->_setDefParams($Params);
$DOMImplementationList = &new DOMImplementationList;
$DOMImplementationSource = &new DOMImplementationSource;
$DOMErrorHandler = &new DOMErrorHandler;
$DOMImplementation = &new DOMImplementation('Document','DocumentType');
$DOMImplementation->_setFeature('CORE','1.0','Document');
$DOMImplementation->_setFeature('CORE','2.0','Document');
$DOMImplementation->_setFeature('CORE','3.0','Document');