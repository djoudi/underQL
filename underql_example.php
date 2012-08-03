<?php

require_once('UQLConnection.php');
require_once('UQLAbstractEntity.php');
require_once('UQLQuery.php');
require_once('UQLQueryPath.php');


$c = new UQLConnection('localhost','underql','root','root','utf8');
$a = new UQLAbstractEntity('demo',$c);

$path = new UQLQueryPath($c,$a);

$path->plugin->toXML()

function UQLPlugin_toXML(/*UQLQueryPath*/object)
{
	
}
//UQLEnvironment // to link all data that releated to different classes // singlton
?>