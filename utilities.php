<?php

function include_filters()
{
  $params = func_get_args();
 
  if(func_num_args() == 0)
   die('You must pass one filter at least to include_filters');
   
  foreach($params as $key => $filter)
   require_once(__DIR__.'/'.UQL_DIR_FILTER.UQL_DIR_FILTER_API.'uql_filter_'.$filter.'.php');
}

function include_rules()
{
  $params = func_get_args();
  
  if(func_num_args() == 0)
   die('You must pass one rule at least to include_rules');
   
  foreach($params as $key => $rule)
   require_once(__DIR__.'/'.UQL_DIR_RULE.UQL_DIR_RULE_API.'uql_rule_'.$rule.'.php');
}

function _f($entity_name)
{
    $GLOBALS[sprintf(UQL_FILTER_OBJECT_SYNTAX,$entity_name)] = new UQLFilter($entity_name);
    return $GLOBALS[sprintf(UQL_FILTER_OBJECT_SYNTAX,$entity_name)];
}

function _r($entity_name)
{
    $GLOBALS[sprintf(UQL_RULE_OBJECT_SYNTAX,$entity_name)] = new UQLRule($entity_name);
    return $GLOBALS[sprintf(UQL_RULE_OBJECT_SYNTAX,$entity_name)];
}
?>