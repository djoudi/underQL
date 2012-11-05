<?php

class UQLUpdateQuery{
	
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
	

public function update($extra ='',$clear_values = true)
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
  $query = $this->formatUpdateQuery($extra);
  	
  if($clear_values)
   $this->values_map = new UQLMap();
  
   return $this->query->executeQuery($query);
}

public function updateWhereID($id,$id_name = 'id')
{
  return $this->update("WHERE `$id_name` = $id");
}


}
?>