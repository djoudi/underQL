<?php

class UQLBase {
	
	//public function freeResources(){}
	public function the_uql_error($message) {
		die ( '<h3><code><b style = "color:#FF0000">UnderQL Error: </b>' . $message . '</h3>' );
	}
	
	public function _() {
		
		$params_count = func_num_args ();
		if ($params_count < 1)
			$this->error ( 'You must pass one parameter at least for _ method' );
		
		$params = func_get_args ();
		$func_name = 'the_uql_' . $params [0];
		if (! method_exists ( $this, $func_name ))
			$this->the_uql_error ( $params [0] . ' is not a valid action' );
		$params = array_slice ( $params, 1 );
		return call_user_func_array ( array ($this, $func_name ), $params );
	}

}
?>