<?php

$uql_plugin_loaded_list = array('toXML','toJSON');

class UQLPlugin {

    private $uql_path;

    private function __construct() {
        $this->uql_path = null;
    }

    public function setPathObject($pobject) {
        $this->uql_path = $pobject;
    }
    public function __call($function_name,$parameters) {
        global $uql_plugin_loaded_list;
        if(array_key_exists($function_name, $uql_plugin_loaded_list))
            return $function_name($this->uql_path);
        return "Uknown Plugin[$function_name]";
    }

    public function __destruct() {
        $this->uql_path = NULL;
    }
}



?>