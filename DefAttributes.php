<?php
/*
 * PHP DOM: Implementation of the Document Object Model (DOM) Level 3 Core Specification <http://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226> in PHP.
 * Jonas Raoni Soares da Silva <http://raoni.org>
 * https://github.com/jonasraoni/php-dom
 */

//Implement this default tagnames/namespaces attributes as you want...

$DefAttribs = array(
	'A'=>array(
		'href',
		'title',
		'target=self',
		'style'
	),
	'FONT'=>array(
		'face=verdana, arial',
		'size=3',
		'color=black',
		'style'
	),
	'TABLE'=>array(
		'cellpadding=1',
		'cellspacing=1',
		'border=0',
		'style'
	),
	'TD'=>array(
		'bgcolor',
		'rowspan=1',
		'colspan=1',
		'background',
		'style',
		'nowrap'
	)
)