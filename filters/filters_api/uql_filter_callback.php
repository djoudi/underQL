<?php

function ufilter_callback($name, $value, $in_out, $params = null) {
	if ($params == null)
		return $value;
	
	return $params [0] ( $value );
}
?>