<?php

class UQLRuleMessagesHandler extends UQLBase{

  private $uql_messages;
  
  public function __construct(&$the_msgs_list)
  {
    if(!$the_msgs_list) 
      $this->uql_messages = array();
    else
      $this->uql_messages = $the_msgs_list; 
  }
  
  public function in($field_name)
  {
    return isset($this->uql_messages[$field_name]);
  }
  
  public function at($field_name,$rule_name)
  {
     return isset($this->uql_messages[$field_name][$rule_name]);
  }
  
  public function get($field_name,$rule_name)
  {
     return $this->uql_messages[$field_name][$rule_name];
  }
  
  public function __destruct()
  {
     $this->uql_messages = null;
  }
}

?>