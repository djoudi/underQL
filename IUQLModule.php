<?php 

interface IUQLModule{

  public function init();
  public function in(&$values,$is_insert = true);
  public function out(&$path);
  public function shutdown();
}

?>