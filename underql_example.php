<?php
ini_set('display_errors', 1);

function urule_devaz($name,$value,$alias=null,$params = null)
{ 
  if(strlen($value) > 10)
   return "The length must be less than or equal to 10!";
   
   return UQL_RULE_SUCCESS;
}

require_once('underQL.php');
require_once('filters/uql_filter_html.php');

$_('users');

$users_filter = new UQLFilter('users');
$users_filter->name('SQLi',UQL_FILTER_IN);
$users_filter->name('stripTags',UQL_FILTER_IN);

$users_rule = new UQLRule('users');
$users_rule->name('devaz');

$users->name = "abdullaheid";

$r = $users->insert();


if(!$users->areRulesPassed())
 {
  echo '<pre>';
  var_dump($users->getMessagesList());
  echo '</pre>';
 }

?>