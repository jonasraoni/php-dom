<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */
?>
<HTML><Head><Title>DOM Test Page</Title>
<Style Type="Text/CSS">
UL {List-Style-Type: none;}
</Style>
</Head>
<Body><Font Face="Verdana" Size="2">
<?PHP
require_once('DOM.php');
Class TBenchmark {
	var $Final, $Start, $ReportFormat;
	function TBenchmark () { $this->Start = $this->Final = 0;}
	function GetTime(){ list($usec, $sec) = explode(" ",microtime()); return ((float)$usec + (float)$sec); }
	function Init(){ $this->Start = $this->GetTime(); }
	function Stop(){ $this->Final = $this->GetTime(); }
	function Report($Format='TIME ELAPSED: %TIME% ms',$TimeUnit='ms') {
		$Time=$this->Final-$this->Start;
		Switch ($TimeUnit) {
			Case 'mic': $Time*=pow(10,6); Break;
			Case 'ms': $Time*=pow(10,3); Break;
			Case 's': Break;
			Case 'm': $Time/=60; Break;
			Case 'h': $Time/=3600; Break;
		}
		echo $Time<0 ? 'Error: Initial Time Is Greater Than The Final' : preg_replace('{%TIME%}',$Time,$Format);
		return preg_replace('{%TIME%}',$Time,$Format);
	}
}
$Benchmark = new TBenchmark();
$Benchmark->Init();

#Document creation
$Doc=&new Document();
#Appending the root element...
$Root = &$Doc->appendChild($Doc->createElement('TREE_ROOT'));

$Root->appendChild($Doc->createTextNode(' THIS '));
$Root->appendChild($Doc->createTextNode(' TEXT'));
$Root->appendChild($Doc->createTextNode(' NODES'));
$TEXT=&$Root->appendChild($Doc->createTextNode(' WERE'));
$Root->appendChild($Doc->createTextNode(' MERGED'));
$TEXT->_mergeAdjacentTexts();

#Append some children to the root
$A=&$Root->appendChild($Doc->createElement('A'));
$TABLE=&$A->appendChild($Doc->createElement('TABLE'));
$NEW=&$TABLE->insertBefore($Doc->createElement('NEW'),$REF=&$TABLE->appendChild($Doc->createElement('REF')));

$FIRST=&$TEXT;
$LAST=&$REF;
echo $FIRST->nodeName.' '.$POSITION_STRINGS[$FIRST->compareDocumentPosition($LAST)].' '.$LAST->nodeName;


#Creating an namespaced element...
$NSElement = &$Doc->createElementNS('http://www.jonas.org/A/','DOC:A');
#Adding childNodes to it...
$NSElement->appendChild($Doc->createElementNS('http://www.jonas.org/B/','DOC:B'));
$NSElement->appendChild($Doc->createElementNS('http://www.jonas.org/Z/','DOC:Z'));
$NSElement->appendChild($Doc->createElementNS('http://www.jonas.org/C/','DOC:C'));
$Root->appendChild($NSElement);

#Creating a DocumentFragment
$docFrag = &$Doc->createDocumentFragment();
$docFrag->appendChild($Doc->createElementNS('http://www.jonas.org/D/','DOC:D'));
$docFrag->appendChild($Doc->createElementNS('http://www.jonas.org/E/','DOC:E'));
#Adding a new node that will be used as a reference soon...
$TestNode=&$Root->appendChild($Doc->createElement('TEST'));
#When adding DocumentFragment nodes, only its children are appended.
$Root->insertBefore($docFrag, $TestNode);

//$Elements = &new NodeList;
//$Elements->_merge($Doc->getElementsByTagNameNS('*','*'));
//$Elements->_merge($Doc->getElementsByTagName('*'));

Function EchoDetails($node) {
	$nodeTypes = array('ELEMENT_NODE','ATTRIBUTE_NODE','TEXT_NODE','CDATA_SECTION_NODE','ENTITY_REFERENCE_NODE','ENTITY_NODE','PROCESSING_INSTRUCTION_NODE','COMMENT_NODE','DOCUMENT_NODE', 'DOCUMENT_TYPE_NODE', 'DOCUMENT_FRAGMENT_NODE', 'NOTATION_NODE');
         $Desc= '»»<B>Node Name: '.$node->nodeName.'</B><BR>'.'»<B>Node Type:</B> '.$nodeTypes[$node->nodeType-1].'<BR>';

         if ($node->parentNode) {
         	$Desc.='»»<B> Parent Node:</B> '.$node->parentNode->nodeName.'<BR>';
         	if(!$node->parentNode->parentNode) {
			$Desc.='» This is the main node, the documentElement<BR>';
                 } else {
	         	$Desc.=($node->previousSibling ? '»<B> Previous Sibling:</B> '.$node->previousSibling->nodeName.'<BR>' : '» This is the first child<BR>');
			$Desc.=($node->nextSibling ? '»»<B> Next Sibling:</B> '.$node->nextSibling->nodeName.'<BR>' : '»» This is the last child<BR>');
			$Desc.=($node->lastChild && $node->firstChild ? '»<B> First Child:</B> '.$node->firstChild->nodeName.'<BR>'.'»»<B> Last Child:</B> '.$node->lastChild->nodeName.'<BR>' : '» This node doesn\'t have children nodes<BR>');
            	}
         }
         else $Desc.='»» This is the implementation node<BR>';
	if($node->_canHaveAttribute) {
         	if($node->attributes->length>0) {
			$Desc.='»»<B> Attributes: </B>';
                         ForEach($node->attributes->_nameList As $attr) $Desc.="[$attr->name=$attr->value]";
		}else $Desc.='»» This node doesn\'t have attributes';
	}
	echo "<Font Size=\"2\" OnMouseOut=\"RemoveIt()\" Description=\"$Desc\">» $node->nodeName</Font><BR>";
}

Function &RecursiveTree(&$Element,$indent){
	If($Element->hasChildNodes()) {
		echo '<UL>';
		For($i=0; $i<$Element->childNodes->length; $i++) {
			echo '<LI>';
			$Child = &$Element->childNodes->item($i);
			EchoDetails($Child);
			echo '</LI>';
			RecursiveTree(&$Child,$indent+100);
		}
		echo '</UL>';
	}
}
echo '<Table Style="Border: 3 Double Gray"><TH BGColor="Silver" Description="This is only a simple DOM structure tree view">DOCUMENT TREE</TH><TR><TD><UL><LI>';
EchoDetails($Doc);
RecursiveTree($Doc,0);
echo '</LI></UL></TD></TR></Table>';
?>
<Script Language="JavaScript">
function BindToDocumentMouseMove() {
	document.onmousemove = function(NSEvent){
		ShowIt(navigator.appName.toLowerCase().indexOf('netscape')!=-1 ? NSEvent : event);
	}
}

BindToDocumentMouseMove();

function RemoveIt(){
	if(Div=document.getElementById('Tooltip')) Div.parentNode.removeChild(Div);
}

function ShowIt(MyEvent, Element){
	Target = navigator.appName.toLowerCase().indexOf('netscape')!=-1 ? MyEvent.target : MyEvent.srcElement;
	MousePos = {X:MyEvent.clientX, Y:MyEvent.clientY};
         if(Element) Target = Element;
         while(Target.parentNode) {
         	if(Target.getAttribute && Target.getAttribute('Description')) {var found=true; break };
         	Target = Target.parentNode;
         }
         if(!found) return false;
	RemoveIt()
	var Div=document.createElement('DIV');
	with(Div.style) {
		position = 'absolute';
		top = MousePos.Y+10;
		left = MousePos.X+10;
                 padding = '10 10 10 10';
		border = '3 Ridge Gray';
		backgroundColor = 'LightYellow';
		fontFamily = 'Verdana, Arial';
                 fontSize = '8pt';
	}
	Div.id = 'Tooltip';
	Div.innerHTML = Target.getAttribute('Description');
	document.body.appendChild(Div);
	Div.onmouseout = function() {
		this.parentNode.removeChild(this);
	}
}
</Script>
<?PHP
$Benchmark->Stop();
$Benchmark->Report('TIME ELAPSED: %TIME% micro','mic');
?>
</Font></Body></HTML>