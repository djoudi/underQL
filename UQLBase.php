<?php

class UQLBase{

 //public function freeResources(){}
 public function error($message)
 {
   die('<h3><code><b style = "color:#FF0000">UnderQL Error: </b>'.$message.'</h3>');
 }

 function _(){

     $params_count = func_num_args();
     if($params_count < 1)
         $this->error('You must pass one parameter at least for _ method');

       $params = func_get_args();
       $func_name = $params[0];
       $params = array_slice($params,1);
       return call_user_func_array(array($this,$func_name),$params);
 }
}

?>