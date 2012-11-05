<?php

require_once('UQLConnection.php');
require_once('UQLAbstractEntity.php');
require_once('UQLQuery.php');
require_once('UQLQueryPath.php');
require_once('UQLMap.php');
require_once('UQLFilter.php');
require_once('UQLInsertQuery.php');
require_once('UQLUpdateQuery.php');
require_once('UQLDeleteQuery.php');


$c = new UQLConnection('localhost','abdullaheid_db','root','root','utf8');
$c->startConnection();
$a = new UQLAbstractEntity('users',$c);
$path = new UQLQueryPath($c,$a);
$add = new UQLUpdateQuery($c,$a);
$d = new UQLDeleteQuery($c,$a);


$f = new UQLFilter($a->getEntityName());

$f->name(UQL_FILTER_IN,'xss');
$f->name(UQL_FILTER_OUT,'xss');
$f->email(UQL_FILTER_IN,'is_email');
$f->password(UQL_FILTER_IN | UQL_FILTER_OUT,'php','<?php echo "WELCOME"; ?>');

//echo '<pre>';
//var_dump($f);
//echo '</pre>';

$v = 10;

${sprintf(UQL_FILTER_OBJECT_SYNTAX,'users')} = 10;

echo $the_users_filter;
//$path->plugin->toXML();

//function UQLPlugin_toXML(/*UQLQueryPath*/$object)
//{
	
//}
//UQLEnvironment // to link all data that releated to different classes // singlton
?>