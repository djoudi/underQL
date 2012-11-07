<?php

function ufilter_html($name,$value,$in_out,$params = null)
{
  if($in_out == UQL_FILTER_OUT)
   return $params[0].$value.$params[1];
   
   return $value;
}


function ufilter_uname($name,$value,$in_out,$params = null)
{
    if($in_out == UQL_FILTER_OUT)
    {
        return ufilter_html($value);
    }
    else
        return $value;
}

?>