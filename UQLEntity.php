<?php

class UQLEntity
{
   private $uql_abstract_entity;
   private $uql_database_handle;
   private $uql_path;
   private $uql_change;
   private $uql_delete;
   
   public function __construct($entity_name,&$database_handle)
   {
     
      $this->uql_abstract_entity = new UQLAbstractEntity($entity_name,$database_handle);
      $this->uql_database_handle = $database_handle;
      $this->uql_path = null;
      $this->uql_change = new UQLChangeQuery($database_handle,$this->uql_abstract_entity);
      $this->uql_delete = new UQLDeleteQuery($database_handle,$this->uql_abstract_entity);
   }
   
   public function __set($name,$value)
   {
     $this->uql_change->$name = $value;
   }
   
   public function __get($name)
   {
    return $this->uql_change->$name;
   }
   
   public function save()
   {
    return $this->uql_change->save();
   }
   
   public function saveFromArray($the_array)
   {
     //$array_count = @count($the_array);
     foreach($the_array as $key => $value)
     {
       if($this->uql_abstract_entity->isFieldExist($key))
        $this->$key = $value;
     }
     
     $this->save();
   }
   
   public function modify($extra)
   {
    return $this->uql_change->modify($extra);
   }
   
   public function modifyWhereID($id,$id_name = 'id')
   {
    return $this->uql_change->modifyWhereID($id,$id_name);
   }
   
   public function delete($extra = '')
   {
     return $this->uql_delete->delete($extra);
   }
   
   public function deleteWhereID($id,$id_name = 'id')
   {
    return $this->uql_delete->deleteWhereID($id,$id_name);
   }
   
   public function query($query)
   {
   
     $this->uql_path = new UQLQueryPath($this->uql_database_handle,$this->uql_abstract_entity);
     if($this->uql_path->executeQuery($query))
      return $this->uql_path;
      
      return false;
   }
   
   public function select($fields = '*',$extra = '')
   {
     $query = sprintf("SELECT %s FROM `%s` %s",$fields,
                      $this->uql_abstract_entity->getEntityName(),$extra);
     
     return $this->query($query);
   }
   
   public function selectWhereID($fields,$id,$id_name = 'id')
   {
      return $this->select($fields,"WHERE `$id_name` = $id"); 
   }
   
   public function areRulesPassed()
	{
	   return $this->uql_change->areRulesPassed();
	}
	
   public function getMessagesList()
	{
	 return $this->uql_change->getMessageList();  
	}
   
   public function __destruct()
   {
      $this->uql_abstract_entity = null;
      $this->uql_database_handle = null;
      $this->uql_path = null;
      $this->uql_change = null;
      $this->uql_delete = null;
   }
   
}

?>