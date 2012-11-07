<?php

$uql_plugin_loaded_list = array('toXML','toJSON');

class UQLPlugin {

    private $path;

    private function __construct() {
        $this->path = null;
    }

    public function setPathObject($pobject) {
        $this->path = $pobject;
    }
    public function __call($function_name,$parameters) {
        global $uql_plugin_loaded_list;
        if(array_key_exists($function_name, $uql_plugin_loaded_list))
            return $function_name($this->path);
        return "Uknown Plugin[$function_name]";
    }

    public function __destruct() {
        $this->path = NULL;
    }
}



?>