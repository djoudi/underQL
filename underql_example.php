<?php

require_once('UQL.php');
require_once('UQLConnection.php');
require_once('UQLAbstractEntity.php');
require_once('UQLQuery.php');
require_once('UQLQueryPath.php');
require_once('UQLMap.php');
require_once('UQLFilter.php');
require_once('UQLFilterEngine.php');
require_once('UQLChangeQuery.php');
require_once('UQLDeleteQuery.php');
require_once('UQLRule.php');
require_once('UQLRuleEngine.php');
require_once('UQLEntity.php');
require_once(UQL_DIR_FILTER.'uql_filter_html.php');

$db = new UQLConnection('localhost','abdullaheid_db','root','root','utf8');
$the_users = new UQLEntity('users',$db);

//$the_users_filter = new UQLFilter('users');
//$the_users_filter->name('html',UQL_FILTER_OUT,'<h1><b>','</b></h1>');


$the_users->id = 5007;
$the_users->name = 'شبكة عبدالله عيد التعليمية';
$the_users->email = "cs.abdullah@hotmail.com";
$the_users->description = "مبرمج ومحب للخير";

$the_users->save();



//UQLEnvironment // to link all data that releated to different classes // singlton
?>