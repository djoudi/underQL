<?php

function urule_isemail($name, $value, $alias = null, $params = null) {
	if(strlen(trim($value)) == 0)
		return "$value is required";
	
	return true;
}
?>