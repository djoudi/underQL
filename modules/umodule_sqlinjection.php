<?php 



class umodule_sqlinection extends UQLModule implements IUQLModule {

    public function init() {

        $this->isOutput(false);
    }

    public function in(&$values,$is_insert = true) {

        for($i = 0; $i < @count($values); $i++)
          $values[$i] = @mysql_real_escape_string($values[$i]);
    }

    public function out(&$path) {

    // No implementation
        
    }

    public function shutdown() {
        
    }
}

?>