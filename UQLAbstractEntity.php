<?php

class UQLAbstractEntity {

	private $entity_name;
	private $fields;
	private $fields_count;

	public function __construct($entity_name, $database_handle) {

		if (($database_handle instanceof UQLConnection)) {
			$this -> entity_name = $entity_name;
			$local_string_query = sprintf("SHOW COLUMNS FROM `%s`", $this -> entity_name);
			$local_query_result = mysql_query($local_string_query, $database_handle -> getDatabaseHandle());
			if ($local_query_result) {
				$this -> fields_count = mysql_num_rows($local_query_result);
				@mysql_free_result($local_query_result);

				$local_fields_list = mysql_list_fields($database_handle -> getDatabaseName(), $this -> entity_name);
				$this -> fields = array();

				$local_i = 0;
				while ($local_i < $this -> fields_count) {
					$local_field = mysql_fetch_field($local_fields_list);
					$this -> fields[$local_field -> name] = $local_field;
					$local_i++;
				}
			}
			else{
				die(mysql_error($database_handle->getDatabaseHandle()));
			}
		} else {
			$this -> entity_name = null;
			$this -> fields = null;
			$this -> fields_count = 0;
		}
	}

}
?>