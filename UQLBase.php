<?php

class UQLBase{

 public function freeResources(){}
 
 public function error($message)
 {
   die('<h3><code><b style = "color:#FF0000">UnderQL Error: </b>'.$message.'</h3>');
 }
 
}

?>