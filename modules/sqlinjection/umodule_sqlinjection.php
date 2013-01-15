<?php 

class umodule_sqlinjection extends UQLModule implements IUQLModule {

    public function init() {
        $this->isOutput(false);
    }

    public function in(&$values,$is_insert = true) {
        foreach($values as $field_name => $val)
          $values[$field_name] = mysql_real_escape_string($val); 
    }

    public function out(&$path) {
    // No implementation
    }

    public function shutdown() {
    // No implementation
    }
}

?>