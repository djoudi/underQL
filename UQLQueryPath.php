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
            die('You must provide a appropriate value for abstract_entity');

        $this ->uql_query_object = new UQLQuery($database_handle);
        $filter_object = UQLFilter::findFilterObject($this->uql_abstract_entity->getEntityName());
        $this ->uql_filter_engine = new UQLFilterEngine($filter_object,UQL_FILTER_OUT);
    }

    public function executeQuery($query) {
        
        if ($this ->uql_query_object -> executeQuery($query)) {
            if ($this ->uql_query_object -> getCount() > 0) {
                $this -> getNext();
                return true;
            }
        }

        return false;

    }

    public function getNext() {
        return $this ->uql_query_object -> fetchRow();
    }

    public function getCount() {
        return $this->uql_query_object->getCount();
    }

    public function getQueryObject() {
        return $this->uql_query_object;
    }

    public function getAbstractEntity() {
        return $this->uql_abstract_entity;
    }

    public function __get($key) {

        if (!$this ->uql_abstract_entity -> isFieldExist($key))
            return "Unknown field [$key]";

        $local_current_query_fields = $this ->uql_query_object -> getCurrentQueryFields();
        if ($local_current_query_fields == null)
            return "Unknown";

        foreach ($local_current_query_fields as $local_field_name) {
            if (strcmp($key, $local_field_name) == 0) {
                $local_current_row = $this ->uql_query_object -> getCurrentRow();
                if ($local_current_row == null)
                    return "Unknown";
                else {
                    return $this->uql_filter_engine->applyFilter($key,$local_current_row -> $key);
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