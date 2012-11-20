<?php

function ufilter_trim($name,$value,$in_out,$params = null)
{
  if($in_out == UQL_FILTER_IN)
   return trim($value);
  else
    return $value;
}


?>