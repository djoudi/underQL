<?php

class underQL
{
  private $database_handle;
  private $entity_list; // load all table names from current database
  private $loaded_entity_list;
  
  	public function __construct($host, $database_name, $user = 'root', $password = '', $charset = 'utf8') {
  	{
  	  $this->database_handle = new UQLConnection($host, $database_name, $user, $password, $charset);
  	  $this->entityListInit();
  	  $this->loaded_entity_list = array();
  	}
  	
  	protected function entityListInit()
  	{
  	  	$local_string_query = sprintf("SHOW TABLES FROM `%s`", $this ->$database_handle->getDatabaseName());
		$local_query_result = mysql_query($local_string_query/*, $database_handle -> getConnectionHandle()*/);
			if ($local_query_result) {
				$tables_count = mysql_num_rows($local_query_result);
				
				while ($local_entity = mysql_fetch_field($local_query_result)) {
					$this->entity_list[] = $local_entity[0];
				}

				@mysql_free_result($local_query_result);
	
			} else {
				die(mysql_error(/*$database_handle -> getConnectionHandle()*/));
			}
  	}
  	
  	protected function loadEntity($entity_name)
  	{
  	   if(!in_array($entity_name,$this->entity_list))
  	    die($entity_name.' is not a valid table name');
  	    
  	    if(in_array($entity_name,$this->loaded_entity_list))
  	     return; // no action NOP
  	      
  	    $GLOBALS[sprintf(UQL_ENTITY_OBJECT_SYNTAX,$entity_name)]=
  	                        new UQLEntity($entity_name,$this->database_handle); 
  	}
  	
  	public function __invoke($entity_name)
  	{
  	   $this->loadEntity($entity_name);
  	}
  	
  	public function __destruct()
  	{
  	  $this->database_handle->closeConnection();
  	  $this->database_handle = null;
  	  $this->entity_list = null;
  	  $this->loaded_entity_list = null;
  	
  	}
}

?>