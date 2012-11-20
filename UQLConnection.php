<?php

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