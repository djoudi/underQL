<?php

class UQLChangeQuery{
	
	private $query;
	private $abstract_entity;
	//used to save list of [field_name = value] that are comming from current insertion query
	private $values_map;
	private $rule_engine;
	private $rule_engine_results;

	
	public function __construct(&$database_handle,&$abstract_entity)
	{
		if((!$database_handle instanceof UQLConnection) || (!$abstract_entity instanceof UQLAbstractEntity))
		  die('Bad database handle');
		
			$this->query = new UQLQuery($database_handle);
			$this->abstract_entity = $abstract_entity;
			$this->values_map = new UQLMap();
			$this->rule_engine = null;
			$this->rule_engine_results = null;
	}
	
	public function __set($name,$value)
	{
	   if(!$this->abstract_entity->isFieldExist($name))
	    die($name.' is not a valid column name');
	    
	    $this->values_map->addElement($name,$value);
	}
	
	public function __get($name)
	{
   	  if(!$this->abstract_entity->isFieldExist($name))
	    die($name.' is not a valid column name');
	  
	  if(!$this->values_map->isElementExist($name))
	   return null;
	 else
	   return $this->values_map->findElement($name);  
	  
	}
	
	public function areRulesPassed()
	{
	  if($this->rules_engine != null)
	   return $this->areRulesPassed();
	}
	
	public function getMessagesList()
	{
	  return $this->rule_engine_results;
	   
	}
	
	protected function formatInsertQuery()
	{
	  $values_count = $this->values_map->getCount();
	  if($values_count == 0)
	   return "";
	   
	   $insert_query = 'INSERT INTO `'.$this->abstract_entity->getEntityName().'` (';
	   
	   $fields = '';
	   $values = 'VALUES(';
	   
	   $all_values = $this->values_map->getMap();
	   $comma = 0; // for last comma in a string
	   
	   foreach($all_values as $key => $value)
	   {
	     $fields .= "`$key`";
	     $field_object = $this->abstract_entity->getFieldObject($key);
	     if($field_object->numeric)
	      $values .= $value;
	     else // string quote
	      $values .= "'$value'";
	     
	     $comma++;
	     
	     if(($comma) < $values_count)
	      {
	        $fields .= ',';
	        $values .= ',';
	      }
	   }
	   
	   $values .= ')';
	   
	   $insert_query .= $fields.') '.$values;
	   print('<pre>'.$insert_query.'</pre>');
	return $insert_query;
	}
	
protected function saveOrModify($is_save = true,$extra = '')
{
  $values_count = $this->values_map->getCount();
  if($values_count == 0)
	return false;

  $rule_object_name = sprintf(UQL_RULE_OBJECT_SYNTAX,$this->abstract_entity->getEntityName());
  $filter_object_name = sprintf(UQL_FILTER_OBJECT_SYNTAX,$this->abstract_entity->getEntityName());
   
  if(isset($GLOBALS[$rule_object_name]))
   $rule_object = $GLOBALS[$rule_object_name];
  else
   $rule_object = null;
   
  if(isset($GLOBALS[$filter_object_name]))
   $filter_object = $GLOBALS[$filter_object_name];
  else
   $filter_object = null;
   
   if($rule_object != null)
    {
      $this->rule_engine = new UQLRuleEngine($rule_object,$this->values_map);
      $this->rule_engine_results = $this->rule_engine->runEngine();
      
      if(!$this->rule_engine->areRulesPassed())
       return false;
    }
    
   if($filter_object != null)
    {
      $fengine = new UQLFilterEngine($filter_object,$this->values_map);
      $this->values_map = $fengine->runEngine();
    }
  
  if($is_save)
   $query = $this->formatInsertQuery();
  else
   $query = $this->formatUpdateQuery($extra);
  	
   // clear values
   $this->values_map = new UQLMap();
  
   return $this->query->executeQuery($query);
}

public function save()
{
  return $this->saveOrModify();
}

protected function formatUpdateQuery($extra = '')
	{
	  $values_count = $this->values_map->getCount();
	  if($values_count == 0)
	   return "";
	   
	   $update_query = 'UPDATE `'.$this->abstract_entity->getEntityName().'` SET ';
	   
	   $fields = '';
	   
	   $all_values = $this->values_map->getMap();
	   $comma = 0; // for last comma in a string
	   
	   foreach($all_values as $key => $value)
	   {
	     $fields .= "`$key` = ";
	     $field_object = $this->abstract_entity->getFieldObject($key);
	     if($field_object->numeric)
	      $fields .= $value;
	     else // string quote
	      $fields .= "'$value'";
	     
	     $comma++;
	     
	     if(($comma) < $values_count)
	      {
	        $fields .= ',';
	      }
	   }
	   
	   $update_query .= $fields.' '.$extra;
	   
	return $update_query;
}
	

public function modify($extra ='')
{
  $this->saveOrModify(false,$extra);
}

public function modifyWhereID($id,$id_name = 'id')
{
  return $this->modify("WHERE `$id_name` = $id");
}

}
?>