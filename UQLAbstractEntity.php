<?php

class UQLAbstractEntity {

	private $entity_name;
	private $fields;
	private $fields_count;

	public function __construct($entity_name, $database_handle) {

		if (($database_handle instanceof UQLConnection)) {
			$this -> entity_name = $entity_name;
			$local_string_query = sprintf("SHOW COLUMNS FROM `%s`",$this->entity_name);
			$local_query_result = mysql_query($local_string_query,$database_handle->getDatabaseHandle());
			if($local_query_result)
			{
				$this->fields_count = 
			}
		} else {
			$this -> entity_name = null;
		}
	}

}
?>