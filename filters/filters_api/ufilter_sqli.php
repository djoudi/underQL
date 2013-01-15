<?php

function ufilter_sqli($name, $value, $in_out, $params = null) {
	if ($in_out == UQL_FILTER_IN)
		return mysql_real_escape_string ( $value );
	
	return $value;
}
?>