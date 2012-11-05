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
$path = new UQLQueryPath($c,$a);
$add = new UQLInsertQuery($c,$a);

$add->id = 500;
$add->name = 'underQL';
$add->password = 'LQrednu';
$add->email = 'abdullaheid@underql.com';

//echo $add->insert();

$path->executeQuery('SELECT * FROM `users`');

echo $path->email;

$path->getNext();
echo '<br />';

echo $path->email;




//$path->plugin->toXML();

//function UQLPlugin_toXML(/*UQLQueryPath*/$object)
//{
	
//}
//UQLEnvironment // to link all data that releated to different classes // singlton
?>