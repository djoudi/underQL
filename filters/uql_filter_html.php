<?php

function ufilter_html($name,$value,$in_out,$params = null)
{
  if($in_out == UQL_FILTER_OUT)
   return $params[0].$value.$params[1];
   
   return $value;
}

?>