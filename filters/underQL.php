<?php

require_once('UQL.php');
require_once('UQLConnection.php');
require_once('UQLMap.php');
require_once('UQLAbstractEntity.php');
require_once('UQLFilter.php');
require_once('UQLFilterEngine.php');
require_once('UQLRule.php');
require_once('UQLRuleEngine.php');
require_once('UQLQuery.php');
require_once('UQLQueryPath.php');
require_once('UQLChangeQuery.php');
require_once('UQLDeleteQuery.php');
require_once('UQLEntity.php');
require_once('UQLPlugin.php');


class underQL
{
  private $database_handle;
  private $entity_list; // load all table names from current database
  private $loaded_entity_list;
  
  	public function __construct(
  	     $host = UQL_DB_HOST,
  	     $database_name = UQL_DB_NAME,
  	     $user = UQL_DB_USER,
  	     $password = UQL_DB_PASSWORD,
  	     $charset = UQL_DB_CHARSET) {
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

$_ = new underQL();

?>