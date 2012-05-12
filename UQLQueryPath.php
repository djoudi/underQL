<?php

class UQLQueryPath {

	public $abstract_entity; // reference to the abstract table's data
	public $query;
	private $columns_buffer;
	// is a map that used by __get to save undefined attributes.

	public function __construct(&$database_handle, &$abstract_entity) {
		if ($abstract_entity instanceof UQLAbstractEntity)
			$this -> abstract_entity = $abstract_entity;
		else
			$this -> abstract_entity = null;

		$this -> query = new UQLQuery($database_handle);
		$this -> columns_buffer = new UQLMap();
	}

	public function __get($key) {
		if (!$this -> abstract_entity -> isFieldExist($key))
			return "Unknown";

		$local_current_query_fields = $this -> query -> getCurrentQueryFields();
		if ($local_current_query_fields == null)
			return "Unknown";

		foreach ($local_current_query_fields as $local_field_name) {
			if (strcmp($key, $local_field_name) == 0) {
				$local_current_row = $this -> query -> getCurrentRow();
				if ($local_current_row == null)
					return "Unknown";
				else
					return $local_current_row -> $key;
			}
		}

		return "Unknown";
	}

}
?>