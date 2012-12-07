<?php

/****************************************************************************************
 * Copyright (c) 2012, Abdullah E. Almehmadi - www.abdullaheid.net                      *
 * All rights reserved.                                                                 *
 ****************************************************************************************
   Redistribution and use in source and binary forms, with or without modification,     
 are permitted provided that the following conditions are met:                         
 
   Redistributions of source code must retain the above copyright notice, this list of 
 conditions and the following disclaimer.
 
   Redistributions in binary form must reproduce the above copyright notice, this list 
 of conditions and the following disclaimer in the documentation and/or other materials
 provided with the distribution.

   Neither the name of the underQL nor the names of its contributors may be used to
 endorse or promote products derived from this software without specific prior written 
 permission.

   THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
 MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL
 THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT
 OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
 TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *****************************************************************************************/

class UQLBase {
	
	public static function underql_error($message) {
		die ( '<h3><code><b style = "color:#FF0000">UnderQL Error: </b>' . $message . '</h3> <br />' );
	}
	
	public static function underql_warning($message)
	{
	  	echo '<h3><code><b style = "color:#0000FF">UnderQL Warning: </b>' . $message . '</h3> <br />';
	}
	
	public function _() {
		
		$params_count = func_num_args ();
		if ($params_count < 1)
			UQLBase::underql_error ( '_ method accepts one parameter at least' );
		
		$params = func_get_args ();
		$func_name = 'underql_' . $params [0];
		if (! method_exists ( $this, $func_name ))
			UQLBase::underql_error ( $params [0] . ' is not a valid method' );
		$params = array_slice ( $params, 1 );
		return call_user_func_array ( array ($this, $func_name ), $params );
	}
}
?>