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


$c = new UQLConnection('localhost','abdullaheid_db','root','root','utf8');
$c->startConnection();
$a = new UQLAbstractEntity('users',$c);
$path = new UQLQueryPath($c,$a);
$add = new UQLChangeQuery($c,$a);
$d = new UQLDeleteQuery($c,$a);

$the_users = new UQLEntity('users',$c);

$the_users->id = 1000;
$the_users->name = "Abdullah Eid N Almehmadi";




$the_users_filter = new UQLFilter($a->getEntityName());
$the_users_rule   = new UQLRule($a->getEntityName());

$the_users_filter->name('xss',UQL_FILTER_IN);
$the_users_filter->name('zebra',UQL_FILTER_OUT);
$the_users_filter->email('email',UQL_FILTER_IN);

$the_users_rule->email('isemail');
$the_users_rule->points('iseven');

$add->name = "abdullaheid";
$add->email = "abdullaheid@abdullaheid.eid";
$add->points = '24';

function urule_isemail($name,$value,$alias = null,$params = null)
{
   $GLOBALS['the_user'] = new UQLMap();
   if(!filter_var($value,FILTER_VALIDATE_EMAIL))
    return "$value is not a valid email";
    
    return UQL_RULE_SUCCESS;
}

function urule_iseven($name,$value,$alias = null,$params = null)
{
   if(!is_int($value) || (($value % 2) != 0))
    return "$value is not even";
    
    return UQL_RULE_SUCCESS;
}


function ufilter_xss($name,$value,$in_out,$params = null)
{
  return "www.$value.net";
}

function ufilter_zebra($name,$value,$in_out,$params = null)
{
  return "<b>$value</b>";
}
function ufilter_email($name,$value,$in_out,$params = null)
{
  return "(@$value@)";
}

$the_users->save();
//$the_users->info();


  echo '<pre>';
  var_dump($the_users);
  echo '</pre>';

//UQLEnvironment // to link all data that releated to different classes // singlton
?>