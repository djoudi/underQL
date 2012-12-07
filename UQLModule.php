<?php

class UQLModule extends UQLBase{

 protected $um_module_name;
 protected $um_is_active;
 
 public function __construct($module_name,$is_active)
 {
   $this->um_module_name = $module_name;
   $this->um_is_active = $is_active;
 }
 
 public function stopModule()
 {
   $this->um_is_active = false;
 }
 
 public function restartModule()
 {
   $this->um_is_active = true;
 }
 
 public function isActive()
 {
  return $this->um_is_active;
 }
}

?>