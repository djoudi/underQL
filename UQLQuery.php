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

    public function the_uql_set_database_handle($database_handle) {
        $this -> database_handle(($database_handle instanceof UQLConnection) ? $database_handle : null);
    }

    public function the_uql_get_database_handle() {
        return $this ->uql_database_handle;
    }

    public function the_uql_execute_query($query) {
        if ($this ->uql_database_handle instanceof UQLConnection) {
            $this ->uql_query_result = mysql_query($query /*,$this -> database_handle*/);
            
            $this->the_uql_is_there_any_error();
            
            if (!$this ->uql_query_result)
                return false;
                
            return true;
        }

        return false;
    }

    public function the_uql_get_current_query_fields() {
        if (!$this ->uql_query_result)
            return null;

        $local_fields_count = @mysql_num_fields($this ->uql_query_result);
        if ($local_fields_count == 0)
            return null;

        for ($local_i = 0; $local_i < $local_fields_count; $local_i++)
            $this ->uql_current_query_fields[$local_i] = mysql_field_name($this ->uql_query_result, $local_i);

        return $this ->uql_current_query_fields;
    }

    public function the_uql_fetch_row() {
        if ($this ->uql_query_result) {
            $this ->uql_current_row_object = mysql_fetch_object($this ->uql_query_result);
            return $this ->uql_current_row_object;
        }

        return false;
    }

    public function the_uql_reset_result() {
        if($this->uql_query_result)
            return mysql_data_seek($this->uql_query_result,0);

        return false;
    }

    public function the_uql_get_current_row() {
        return $this ->uql_current_row_object;
    }

    public function the_uql_get_count() {
        if ($this ->uql_query_result)
            return mysql_num_rows($this ->uql_query_result);

        return 0;
    }

    public function the_uql_get_affected_rows() {
        if (($this ->uql_database_handle instanceof UQLConnection) && ($this ->uql_query_result))
            return mysql_affected_rows($this ->uql_database_handle);

        return 0;
    }

    public function the_uql_get_last_inserted_id() {
        if (($this ->uql_database_handle instanceof UQLConnection) && ($this ->uql_query_result))
            return mysql_insert_id($this ->uql_database_handle);

        return 0;
    }

    public function the_uql_free_result() {
        if ($this ->uql_query_result)
            @mysql_free_result($this ->uql_query_result);

        $this ->uql_current_row_object = null;
        $this ->uql_query_result = null;
        $this ->uql_current_query_fields = array();
    }
    
    public function the_uql_is_there_any_error()
    {
      if(mysql_errno() != 0)
         $this->the_uql_error('[MySQL query error - '.mysql_errno().'] - '.mysql_error());      
    }

    public function __destruct() {
        $this->the_uql_free_result();
        $this ->uql_query_result = null;
        $this ->uql_current_query_fields = null;
        $this ->uql_current_row_object = null;
        $this ->uql_database_handle = null;
    }

}
?>