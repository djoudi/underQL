<?php

class UQLQuery extends UQLBase{

    private $uql_database_handle;
    private $uql_query_result;
    private $uql_current_row_object;
    private $uql_current_query_fields;

    public function __construct(&$database_handle) {
        $this ->uql_database_handle = (($database_handle instanceof UQLConnection) ? $database_handle : null);
        $this ->uql_query_result = null;
        $this ->uql_current_row_object = null;
        $this ->uql_current_query_fields = array();
    }

    public function setDatabaseHandle($database_handle) {
        $this -> database_handle(($database_handle instanceof UQLConnection) ? $database_handle : null);
    }

    public function getDatabaseHandle() {
        return $this ->uql_database_handle;
    }

    public function executeQuery($query) {
        if ($this ->uql_database_handle instanceof UQLConnection) {
            $this ->uql_query_result = mysql_query($query /*,$this -> database_handle*/);
            if (!$this ->uql_query_result)
                return false;

            return true;
        }

        return false;
    }

    public function getCurrentQueryFields() {
        if (!$this ->uql_query_result)
            return null;

        $local_fields_count = @mysql_num_fields($this ->uql_query_result);
        if ($local_fields_count == 0)
            return null;

        for ($local_i = 0; $local_i < $local_fields_count; $local_i++)
            $this ->uql_current_query_fields[$local_i] = mysql_field_name($this ->uql_query_result, $local_i);

        return $this ->uql_current_query_fields;
    }

    public function fetchRow() {
        if ($this ->uql_query_result) {
            $this ->uql_current_row_object = mysql_fetch_object($this ->uql_query_result);
            return $this ->uql_current_row_object;
        }

        return false;
    }

    public function resetResult() {
        if($this->uql_query_result)
            return mysql_data_seek($this->uql_query_result,0);

        return false;
    }

    public function getCurrentRow() {
        return $this ->uql_current_row_object;
    }

    public function getCount() {
        if ($this ->uql_query_result)
            return mysql_num_rows($this ->uql_query_result);

        return 0;
    }

    public function getAffectedRows() {
        if (($this ->uql_database_handle instanceof UQLConnection) && ($this ->uql_query_result))
            return mysql_affected_rows($this ->uql_database_handle);

        return 0;
    }

    public function getLastInsertedID() {
        if (($this ->uql_database_handle instanceof UQLConnection) && ($this ->uql_query_result))
            return mysql_insert_id($this ->uql_database_handle);

        return 0;
    }

    public function freeResult() {
        if ($this ->uql_query_result)
            @mysql_free_result($this ->uql_query_result);

        $this ->uql_current_row_object = null;
        $this ->uql_query_result = null;
        $this ->uql_current_query_fields = array();
    }

    public function __destruct() {
        $this->freeResult();
        $this ->uql_query_result = null;
        $this ->uql_current_query_fields = null;
        $this ->uql_current_row_object = null;
        $this ->uql_database_handle = null;
    }

}
?>