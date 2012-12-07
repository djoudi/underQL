<?php 



class umodule_demo extends UQLModule implements IUQLModule{

 public function init()
 {
   echo 'init Demo ..<br />';
 }
 
 public function in(&$values,$is_insert = true)
 {
    echo 'in Demo .. <br />';
 }
 
 public function out(&$path)
 { 
    //$path->_('reset_result');
    $f = $path->_('get_current_query_fields');
    while($path->_('get_next'))
    {
      echo $path->$f[0].' - '.$path->$f[3];
      echo '<br />';
    }
      echo 'out Demo .. <br />';
 }
 
 public function shutdown()
 {
   echo 'shutdown Demo..<br />';
 }
}

?>