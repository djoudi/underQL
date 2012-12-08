<?php

class UQLModule extends UQLBase{

 protected $um_module_name;
 protected $um_is_active;
 protected $um_is_input;
 protected $um_is_output;
 
 public function __construct($module_name,$is_active)
 {
   $this->um_module_name = $module_name;
   $this->um_is_active = $is_active;
   $this->um_is_input = true; // run (in) method if true
   $this->um_is_output = true; // run (out) method if true
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
 
 public function useInput($use)
 {
   $this->um_is_input = $use;
 }
 
 public function useOutput($use)
 {
   $this->um_is_output = $use;
 }
 
 public function isInput()
 {
  return $this->um_is_input;
 }
 
 public function isOutput()
 {
  return $this->um_is_output;
 }
 
 public function __destruct()
 {
   $this->um_module_name = null;
   $this->um_is_active = false;
   $this->um_is_input = false;
   $this->um_is_output = false;
 }
}

?>