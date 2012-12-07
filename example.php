<?php 


//require_once('underQL.php');

define ('UQL_PLUGIN_IN',1);
define ('UQL_PLUGIN_OUT',2);


interface IUQLModule{

  public function init();
  public function in(UQLMap $values);
  public function out(UQLQueryPath &$path);
  public function shutdown();
}

?>