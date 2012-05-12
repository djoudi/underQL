<?php

class UQLQueryPath {

	public $abstract_entity;
	// reference to the abstract table's data
	public $query_object;
	
	public function __construct(&$database_handle, &$abstract_entity) {
		if ($abstract_entity instanceof UQLAbstractEntity)
			$this -> abstract_entity = $abstract_entity;
		else
			$this -> abstract_entity = null;

		$this -> query_object = new UQLQuery($database_handle);
		//$this -> columns_buffer = new UQLMap();
	}

	public function executeQuery($query) {
		if ($this -> query_object -> executeQuery($query)) {
			if ($this -> query_object -> getCount() > 0)
				$this -> getNext();
		}

	}

	public function getNext() {
		return $this -> query_object -> fetchRow();
	}
	
	public function getCount()
	{
		return $this->query_object->getCount();
	}

	public function __get($key) {
		
		if (!$this -> abstract_entity -> isFieldExist($key))
			return "Unknown";

		$local_current_query_fields = $this -> query_object -> getCurrentQueryFields();
		if ($local_current_query_fields == null)
			return "Unknown";

		foreach ($local_current_query_fields as $local_field_name) {
			if (strcmp($key, $local_field_name) == 0) {
				$local_current_row = $this -> query_object -> getCurrentRow();
				if ($local_current_row == null)
					return "Unknown";
				else
					return $local_current_row -> $key;
			}
		}

		return "Unknown";
	}
	
	public function __destruct()
	{
		$this->abstract_entity = null;
		$this->query_object = null;
	}

}
?>