<?php

require_once('UQLConnection.php');
require_once('UQLAbstractEntity.php');
require_once('UQLQuery.php');
require_once('UQLQueryPath.php');
require_once('UQLMap.php');
require_once('UQLInsertQuery.php');

$c = new UQLConnection('localhost','abdullaheid_db','root','root','utf8');
$c->startConnection();
$a = new UQLAbstractEntity('users',$c);

$add = new UQLInsertQuery($c,$a);

$add->id = 500;
$add->name = 'underQL';
$add->password = 'LQrednu';
$add->email = 'abdullaheid@underql.com';

echo $add->insert();

echo '<pre>';
var_dump($a);
echo '</pre>';

//$path = new UQLQueryPath($c,$a);

//$path->plugin->toXML();

//function UQLPlugin_toXML(/*UQLQueryPath*/$object)
//{
	
//}
//UQLEnvironment // to link all data that releated to different classes // singlton
?>