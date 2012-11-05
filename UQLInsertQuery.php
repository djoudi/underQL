<?php

class UQLInsertQuery{
	
	private $query;
	private $abstract_entity;
	//used to save list of [field_name = value] that are comming from current insertion query
	private $values_map;
	
	public function __construct(&$database_handle,&$abstract_entity)
	{
		if((!$database_handle instanceof UQLConnection) || (!$abstract_entity instanceof UQLAbstractEntity))
		  die('Bad database handle');
		
			$this->query = new UQLQuery($database_handle);
			$this->abstract_entity = $abstract_entity;
			$this->values_map = new UQLMap();
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
	

public function insert($clear_values = true)
{
  $values_count = $this->values_map->getCount();
  if($values_count == 0)
	return false;

  $filter_object_name = sprintf(UQL_FILTER_OBJECT_SYNTAX,$this->abstract_entity->getEntityName());
  //echo $filter_object_name;
  if(isset($GLOBALS[$filter_object_name]))
   $filter_object = $GLOBALS[$filter_object_name];
  else
   $filter_object = null;
   
   if($filter_object != null)
    {
      $fengine = new UQLFilterEngine($filter_object,$this->values_map);
      $this->values_map = $fengine->runEngine();
    }
  
  $query = $this->formatInsertQuery();
  	
  if($clear_values)
   $this->values_map = new UQLMap();
  
   return $this->query->executeQuery($query);
}

}
?>