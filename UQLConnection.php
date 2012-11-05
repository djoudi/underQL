<?php

class UQLConnection {

	private $connection_handle;
	private $database_host;
	private $database_user_name;
	private $database_password;
	private $database_name;
	private $operations_charset;
	private $error_message;

	public function __construct($host, $database_name, $user = 'root', $password = '', $charset = 'utf8') {

		$this -> database_host = $host;
		$this -> database_name = $database_name;
		$this -> database_user_name = $user;
		$this -> database_password = $password;
		$this -> operations_charset = $charset;
		$this -> connection_handle = null;
		$this -> error_message = null;
	}

	public function startConnection() {
		$this -> connection_handle = mysql_connect($this -> database_host, $this -> database_user_name, $this -> database_password);
		if (!$this -> connection_handle) {
			$this -> setErrorMessage('Unable to connect');
			return false;
		}

        $this->setDatabaseName($this->database_name);
        
		$local_charset_query = sprintf("SET NAMES '%s'", $this -> operations_charset);
		mysql_query($local_charset_query);
		return $this -> connection_handle;
	}

	public function restartConnection() {
		$this -> closeConnection();
		$this -> startConnection();
	}

	public function getConnectionHandle() {
		return $this -> connection_handle;
	}

	public function setDatabaseHost($host) {
		$this -> database_host = $host;
	}

	public function getDatabaseHost() {
		return $this -> database_host;
	}

	public function setDatabaseName($db_name) {
		$this -> database_name = $db_name;
		$local_result = mysql_select_db($this -> database_name);
		if (!$local_result) {
			$this -> closeConnection();
			$this -> setErrorMessage('Unable to select database');
			return false;
		}

		return true;
	}

	public function getDatabaseName() {
		return $this -> database_name;
	}

	public function setDatabaseUserName($user) {
		$this -> database_user_name = $user;
	}

	public function getDatabaseUserName() {
		return $this -> database_user_name;
	}

	public function setDatabasePassword($password) {
		$this -> database_password = $password;
	}

	public function getDatabasePassword() {
		return $this -> database_password;
	}

	public function setDatabaseCharset($charset, $without_restart = false) {
		/* $without_restart : if true, run a query to change charset without need to restarting the connection*/
		$this -> operations_charset = $charset;
		if ($without_restart) {
			$local_charset_query = sprintf("SET NAMES '%s'", $this -> operations_charset);
			mysql_query($local_charset_query);
		}

	}

	public function getDatabaseCharset() {
		return $this -> operations_charset;
	}

	protected function setErrorMessage($message) {
		$this -> error_message = $message;
	}

	public function getErrorMessage() {
		return $this -> error_message;
	}

	public function closeConnection() {
		if ($this -> connection_handle)
			mysql_close($this -> connection_handle);

		$this -> connection_handle = false;
	}

	public function __destruct() {
		//Clean up
		$this -> closeConnection();
		$this -> database_host = null;
		$this -> database_name = null;
		$this -> database_user_name = null;
		$this -> database_password = null;
		$this -> operations_charset = null;
		$this -> connection_handle = null;
		$this -> error_message = null;
	}

}
?>