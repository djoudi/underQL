<?php

class UQLQuery {

	private $database_handle;
	private $query_result;
	private $current_row_object;
	private $current_query_fields;

	public function __construct($database_handle) {
		$this -> database_handle = (($database_handle instanceof UQLConnection) ? $database_handle : null);
		$this -> query_result = null;
		$this -> current_row_object = null;
		$this -> current_query_fields = array();
	}

	public function setDatabaseHandle($database_handle) {
		$this -> database_handle(($database_handle instanceof UQLConnection) ? $database_handle : null);
	}

	public function getDatabaseHandle() {
		return $this -> database_handle;
	}

	public function executeQuery($query) {
		if ($this -> database_handle instanceof UQLConnection) {
			$this -> query_result = mysql_query($query, $this -> database_handle);
			if (!$this -> query_result)
				return false;

			return true;
		}

		return false;
	}

	public function getCurrentQueryFields() {
		if (!$this -> query_result)
			return null;

		$local_fields_count = @mysql_num_fields($this -> query_result);
		if ($local_fields_count == 0)
			return null;

		for ($local_i = 0; $local_i < $local_fields_count; $local_i++)
			$this -> current_query_fields[$local_i] = mysql_field_name($this -> query_result, $local_i);

		return $this -> current_query_fields;
	}

	public function fetchRow() {
		if ($this -> query_result) {
			$this -> current_row_object = mysql_fetch_object($this -> query_result);
			return $this -> current_row_object;
		}

		return false;
	}
	
	public function resetResult()
	{
		if($this->query_result)
		 return mysql_data_seek($this->query_result,0);
		
		return false;
	}

	public function getCurrentRow() {
		return $this -> current_row_object;
	}

	public function getCount() {
		if ($this -> query_result)
			return mysql_num_rows($this -> query_result);

		return 0;
	}

	public function getAffectedRows() {
		if (($this -> database_handle instanceof UQLConnection) && ($this -> query_result))
			return mysql_affected_rows($this -> database_handle);

		return 0;
	}

	public function getLastInsertedID() {
		if (($this -> database_handle instanceof UQLConnection) && ($this -> query_result))
			return mysql_insert_id($this -> database_handle);

		return 0;
	}

	public function freeResult() {
		if ($this -> query_result)
			@mysql_free_result($this -> query_result);

		$this -> current_row_object = null;
		$this -> query_result = null;
		$this -> current_query_fields = array();
	}

	public function __destruct() {
		$this -> query_result = null;
		$this -> current_query_fields = null;
		$this -> current_row_object = null;
		$this -> database_handle = null;
	}

}
?>