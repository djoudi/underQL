<?php

function ufilter_phpcolor($name, $value, $in_out, $params = null) {
	return highlight_string ( $value, true );
}
?>