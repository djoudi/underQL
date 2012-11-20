<?php

class UQLQueryPath extends UQLBase{

    public $uql_abstract_entity;
    // reference to the abstract table's data
    public $uql_query_object;
    public $uql_filter_engine;

    public function __construct(&$database_handle, &$abstract_entity) {
        
        if ($abstract_entity instanceof UQLAbstractEntity)
            $this ->uql_abstract_entity = $abstract_entity;
        else
            $this->error('You must provide a appropriate value for abstract_entity parameter');

        $this ->uql_query_object = new UQLQuery($database_handle);
        $filter_object = UQLFilter::the_uql_find_filter_object($this->uql_abstract_entity->the_uql_get_entity_name());
        $this ->uql_filter_engine = new UQLFilterEngine($filter_object,UQL_FILTER_OUT);
    }

    public function the_uql_execute_query($query) {
        
        if ($this ->uql_query_object -> the_uql_execute_query($query)) {
            if ($this ->uql_query_object -> the_uql_get_count() > 0) {
                $this -> the_uql_get_next();
                return true;
            }
        }

        return false;

    }

    public function the_uql_get_next() {
        return $this ->uql_query_object -> the_uql_fetch_row();
    }

    public function the_uql_get_count() {
        return $this->uql_query_object->the_uql_get_count();
    }

    public function the_uql_get_query_object() {
        return $this->uql_query_object;
    }

    public function the_uql_get_abstract_entity() {
        return $this->uql_abstract_entity;
    }

    public function __get($key) {

        if (!$this ->uql_abstract_entity -> the_uql_is_field_exist($key))
            $this->the_uql_error("Unknown field [$key]");

        $local_current_query_fields = $this ->uql_query_object -> the_uql_get_current_query_fields();
        if ($local_current_query_fields == null)
            return "Unknown";

        foreach ($local_current_query_fields as $local_field_name) {
            if (strcmp($key, $local_field_name) == 0) {
                $local_current_row = $this ->uql_query_object -> the_uql_get_current_row();
                if ($local_current_row == null)
                    return "Unknown";
                else {
                    return $this->uql_filter_engine->the_uql_apply_filter($key,$local_current_row -> $key);
                }
            }
        }

        return "Unknown";
    }

    public function __destruct() {

        $this->uql_abstract_entity = null;
        $this->uql_query_object = null;
        //$this->plugin = null;
    }

}
?>