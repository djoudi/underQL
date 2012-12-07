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

class UQLConnection extends UQLBase {
	
	private $um_connection_handle;
	private $um_database_host;
	private $um_database_user_name;
	private $um_database_password;
	private $um_database_name;
	private $um_operations_charset;
	
	public function __construct($host, $database_name, $user = 'root', $password = '', $charset = 'utf8') {
		
		$this->um_database_host = $host;
		$this->um_database_name = $database_name;
		$this->um_database_user_name = $user;
		$this->um_database_password = $password;
		$this->um_operations_charset = $charset;
		$this->um_connection_handle = null;
		$this->underql_start_connection ();
	}
	
	public function underql_start_connection() {
		$this->underql_connection_handle = mysql_connect ( $this->um_database_host, $this->um_database_user_name, $this->um_database_password );
		if (! $this->um_connection_handle) {
			$this->underql_error ( 'Unable to connect' );
			return false;
		}
		
		$this->underql_set_database_name ( $this->um_database_name );
		
		$charset_query = sprintf ( "SET NAMES '%s'", $this->um_operations_charset );
		mysql_query ( $charset_query );
		return $this->um_connection_handle;
	}
	
	public function underql_restart_connection() {
		$this->underql_close_connection ();
		$this->underql_start_connection ();
	}
	
	public function underql_get_connection_handle() {
		return $this->um_connection_handle;
	}
	
	public function underql_set_database_host($host) {
		$this->um_database_host = $host;
	}
	
	public function underql_get_database_host() {
		return $this->um_database_host;
	}
	
	public function underql_set_database_name($db_name) {
		$this->um_database_name = $db_name;
		$result = mysql_select_db ( $this->um_database_name );
		if (! $result) {
			$this->underql_close_connection ();
			$this->underql_error ( 'Unable to select database' );
			return false;
		}
		
		return true;
	}
	
	public function underql_get_database_name() {
		return $this->um_database_name;
	}
	
	public function underql_set_database_user_name($user) {
		$this->um_database_user_name = $user;
	}
	
	public function underql_get_database_user_name() {
		return $this->um_database_user_name;
	}
	
	public function underql_set_database_password($password) {
		$this->um_database_password = $password;
	}
	
	public function underql_get_database_password() {
		return $this->um_database_password;
	}
	
	public function underql_set_database_charset($charset, $without_restart = false) {
		/* $without_restart : if true, run a query to change charset without need to restarting the connection*/
		$this->um_operations_charset = $charset;
		if ($without_restart) {
			$charset_query = sprintf ( "SET NAMES '%s'", $this->um_operations_charset );
			mysql_query ( $charset_query );
		}
	
	}
	
	public function underql_get_database_charset() {
		return $this->um_operations_charset;
	}
	
	public function underql_close_connection() {
		if ($this->um_connection_handle)
			mysql_close ( $this->um_connection_handle );
		
		$this->um_connection_handle = false;
	}
	
	public function __destruct() {
		$this->um_database_host = null;
		$this->um_database_name = null;
		$this->um_database_user_name = null;
		$this->um_database_password = null;
		$this->um_operations_charset = null;
		$this->um_connection_handle = null;
	}

}
?>