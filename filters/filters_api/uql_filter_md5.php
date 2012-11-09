<?php

require_once('filters_api/uql_filter_md5.php');

function ufilter_md5($name,$value,$in_out,$params = null)
{
  return md5($value);
}

?>