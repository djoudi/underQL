<?php 



class umodule_demo extends UQLModule implements IUQLModule {

    public function init() {
        echo 'init Demo ..<br />';
    }

    public function in(&$values,$is_insert = true) {
        echo 'in Demo .. <br />';
    }

    public function out(&$path) {

        echo 'out Demo .. <br />';
    }

    public function shutdown() {
        echo 'shutdown Demo..<br />';
    }
}

?>