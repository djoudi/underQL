<?php

class UQLMap extends UQLBase{

    private $uql_map_list;
    private $uql_elements_count;

    public function __construct() {
        $this ->uql_map_list = array();
        $this ->uql_elements_count = 0;
    }

    public function the_uql_add_element($key, $value) {

        if($this->the_uql_find_element($key) == null)
            $this ->uql_elements_count++;

        $this ->uql_map_list[$key] = $value;
    }

    public function the_uql_find_element($key) {
        if ($this -> the_uql_is_element_exist($key))
            return $this ->uql_map_list[$key];

        return null;
    }

    public function the_uql_is_element_exist($key) {
        if ($this ->uql_elements_count <= 0)
            return false;

        if (@array_key_exists($key, $this ->uql_map_list))
            return true;

        return false;
    }

    public function the_uql_get_count() {
        return count($this->uql_map_list);
    }

    public function the_uql_remove_element($key) {

        if ($this -> the_uql_is_element_exist($key)) {
            unset($this -> uql_map_list[$key]);
            $this -> uql_elements_count--;
        }
    }

    public function the_uql_is_empty() {
        return $this -> uql_elements_count == 0;
    }

    public function mapCallback($callback) {
        if (!$this -> isEmpty())
            return array_map($callback, $this -> map_list);
    }

    public function the_uql_get_map() {
        return $this->uql_map_list;
    }
    
    public function __destruct() {
        
        $this -> uql_map_list = null;
        $this -> uql_elements_count = 0;
    }

}
?>