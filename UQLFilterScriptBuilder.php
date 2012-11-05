<?php

require_once('UQLConnection.php');
require_once('UQLAbstractEntity.php');
require_once('UQLFilter.php');



$g_ename = 'users';

$g_dbhandle = new UQLConnection('localhost','abdullaheid_db','root','root','utf8');
$g_dbhandle->startConnection();
$entity = new UQLAbstractEntity($g_ename,$g_dbhandle);

$g_filter_object = sprintf('$'.UQL_FILTER_OBJECT_SYNTAX,$g_ename);

echo '<pre>';
$g_php_start = "&lt;?php\n\n";
$g_php_end   = "?&gt;\n\n";

echo $g_php_start;

echo sprintf($g_filter_object."= new UQLFilter('%s');\n\n",$g_ename);

$g_filter_call = $g_filter_object.'->'."%s(%s,'%s',\$a,\$b,\$c);\n\n";



$g_fields = $entity->getAllFields();

foreach($g_fields as $g_key => $g_value)
{
   echo sprintf($g_filter_call,$g_value->name,'UQL_FILTER_IN | UQL_FILTER_OUT','demo_'.$g_value->name.'_func');
}

echo $g_php_end;

echo '</pre>';

?>