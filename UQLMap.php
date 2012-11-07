<?php
class UQLMap {

    private $uql_map_list;
    private $uql_elements_count;

    public function __construct() {
        $this ->uql_map_list = array();
        $this ->uql_elements_count = 0;
    }

    public function addElement($key, $value) {

        if($this->findElement($key) == null)
            $this ->uql_elements_count++;

        $this ->uql_map_list[$key] = $value;
    }

    public function findElement($key) {
        if ($this -> isElementExist($key))
            return $this ->uql_map_list[$key];

        return null;
    }

    public function isElementExist($key) {
        if ($this ->uql_elements_count <= 0)
            return false;

        if (@array_key_exists($key, $this ->uql_map_list))
            return true;

        return false;
    }

    public function getCount() {
        return count($this->uql_map_list);
    }

    public function removeElement($key) {

        if ($this -> isElementExist($key)) {
            unset($this -> uql_map_list[$key]);
            $this -> uql_elements_count--;
        }
    }

    public function isEmpty() {
        return $this -> uql_elements_count == 0;
    }

    public function mapCallback($callback) {
        if (!$this -> isEmpty())
            return array_map($callback, $this -> map_list);
    }

    public function getMap() {
        return $this->uql_map_list;
    }

    public function __destruct() {

        $this -> uql_map_list = null;
        $this -> uql_elements_count = 0;
    }

}
?>