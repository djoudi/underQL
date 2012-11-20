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

   Neither the name of the <ORGANIZATION> nor the names of its contributors may be used to
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
	
	private $uql_connection_handle;
	private $uql_database_host;
	private $uql_database_user_name;
	private $uql_database_password;
	private $uql_database_name;
	private $uql_operations_charset;
	
	public function __construct($host, $database_name, $user = 'root', $password = '', $charset = 'utf8') {
		
		$this->uql_database_host = $host;
		$this->uql_database_name = $database_name;
		$this->uql_database_user_name = $user;
		$this->uql_database_password = $password;
		$this->uql_operations_charset = $charset;
		$this->uql_connection_handle = null;
		$this->the_uql_start_connection ();
	}
	
	public function the_uql_start_connection() {
		$this->uql_connection_handle = mysql_connect ( $this->uql_database_host, $this->uql_database_user_name, $this->uql_database_password );
		if (! $this->uql_connection_handle) {
			$this->the_uql_error ( 'Unable to connect' );
			return false;
		}
		
		$this->the_uql_set_database_name ( $this->uql_database_name );
		
		$local_charset_query = sprintf ( "SET NAMES '%s'", $this->uql_operations_charset );
		mysql_query ( $local_charset_query );
		return $this->uql_connection_handle;
	}
	
	public function the_uql_restart_connection() {
		$this->the_uql_close_connection ();
		$this->the_uql_start_connection ();
	}
	
	public function the_uql_get_connection_handle() {
		return $this->uql_connection_handle;
	}
	
	public function the_uql_set_database_host($host) {
		$this->uql_database_host = $host;
	}
	
	public function the_uql_get_database_host() {
		return $this->uql_database_host;
	}
	
	public function the_uql_set_database_name($db_name) {
		$this->uql_database_name = $db_name;
		$local_result = mysql_select_db ( $this->uql_database_name );
		if (! $local_result) {
			$this->the_uql_close_connection ();
			$this->the_uql_error ( 'Unable to select database' );
			return false;
		}
		
		return true;
	}
	
	public function the_uql_get_database_name() {
		return $this->uql_database_name;
	}
	
	public function the_uql_set_database_user_name($user) {
		$this->uql_database_user_name = $user;
	}
	
	public function the_uql_get_database_user_name() {
		return $this->uql_database_user_name;
	}
	
	public function the_uql_set_database_password($password) {
		$this->uql_database_password = $password;
	}
	
	public function the_uql_get_database_password() {
		return $this->uql_database_password;
	}
	
	public function the_uql_set_database_charset($charset, $without_restart = false) {
		/* $without_restart : if true, run a query to change charset without need to restarting the connection*/
		$this->uql_operations_charset = $charset;
		if ($without_restart) {
			$local_charset_query = sprintf ( "SET NAMES '%s'", $this->uql_operations_charset );
			mysql_query ( $local_charset_query );
		}
	
	}
	
	public function the_uql_get_database_charset() {
		return $this->uql_operations_charset;
	}
	
	public function the_uql_close_connection() {
		if ($this->uql_connection_handle)
			mysql_close ( $this->connection_handle );
		
		$this->uql_connection_handle = false;
	}
	
	public function __destruct() {
		//Clean up
		//        $this -> closeConnection();
		$this->uql_database_host = null;
		$this->uql_database_name = null;
		$this->uql_database_user_name = null;
		$this->uql_database_password = null;
		$this->uql_operations_charset = null;
		$this->uql_connection_handle = null;
	}

}
?>