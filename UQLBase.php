<?php

class UQLBase{

 //public function freeResources(){}
 public function error($message)
 {
   die('<h3><code><b style = "color:#FF0000">UnderQL Error: </b>'.$message.'</h3>');
 }

 function _(){

     $params_count = func_num_args();
     $params = func_get_args();
     if($params_count < 1)
         $this->error('You must pass one parameter at least for _ method');

     switch($params_count)
     {
         case 1: return $this->$params[0]();
         case 2: return $this->$params[0]($params[1]);
         case 3: return $this->$params[0]($params[1],$params[2]);
         case 4: return $this->$params[0]($params[1],$params[2],$params[3]);
         case 5: return $this->$params[0]($params[1],$params[2],$params[3],$params[4]);
         case 6: return $this->$params[0]($params[1],$params[2],$params[3],$params[4],$params[5]);
         case 7: return $this->$params[0]($params[1],$params[2],$params[3],$params[4],$params[5],$params[6]);
     }

 }
 
}

?>