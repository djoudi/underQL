<?php

class UQLConnection extends UQLBase{

    private $uql_connection_handle;
    private $uql_database_host;
    private $uql_database_user_name;
    private $uql_database_password;
    private $uql_database_name;
    private $uql_operations_charset;
    private $uql_error_message;

    public function __construct($host, $database_name, $user = 'root', $password = '', $charset = 'utf8') {

        $this ->uql_database_host = $host;
        $this ->uql_database_name = $database_name;
        $this ->uql_database_user_name = $user;
        $this ->uql_database_password = $password;
        $this ->uql_operations_charset = $charset;
        $this ->uql_connection_handle = null;
        $this ->uql_error_message = null;
        $this->startConnection();
    }

    public function startConnection() {
        $this ->uql_connection_handle = mysql_connect(
                $this ->uql_database_host, $this ->uql_database_user_name, $this ->uql_database_password);
        if (!$this ->uql_connection_handle) {
            $this -> setErrorMessage('Unable to connect');
            return false;
        }

        $this->setDatabaseName($this->uql_database_name);

        $local_charset_query = sprintf("SET NAMES '%s'", $this ->uql_operations_charset);
        mysql_query($local_charset_query);
        return $this ->uql_connection_handle;
    }

    public function restartConnection() {
        $this -> closeConnection();
        $this -> startConnection();
    }

    public function getConnectionHandle() {
        return $this ->uql_connection_handle;
    }

    public function setDatabaseHost($host) {
        $this ->uql_database_host = $host;
    }

    public function getDatabaseHost() {
        return $this ->uql_database_host;
    }

    public function setDatabaseName($db_name) {
        $this ->uql_database_name = $db_name;
        $local_result = mysql_select_db($this ->uql_database_name);
        if (!$local_result) {
            $this -> closeConnection();
            $this -> setErrorMessage('Unable to select database');
            return false;
        }

        return true;
    }

    public function getDatabaseName() {
        return $this ->uql_database_name;
    }

    public function setDatabaseUserName($user) {
        $this ->uql_database_user_name = $user;
    }

    public function getDatabaseUserName() {
        return $this ->uql_database_user_name;
    }

    public function setDatabasePassword($password) {
        $this ->uql_database_password = $password;
    }

    public function getDatabasePassword() {
        return $this ->uql_database_password;
    }

    public function setDatabaseCharset($charset, $without_restart = false) {
        /* $without_restart : if true, run a query to change charset without need to restarting the connection*/
        $this ->uql_operations_charset = $charset;
        if ($without_restart) {
            $local_charset_query = sprintf("SET NAMES '%s'", $this ->uql_operations_charset);
            mysql_query($local_charset_query);
        }

    }

    public function getDatabaseCharset() {
        return $this ->uql_operations_charset;
    }

    protected function setErrorMessage($message) {
        $this ->uql_error_message = $message;
    }

    public function getErrorMessage() {
        return $this ->uql_error_message;
    }

    public function closeConnection() {
        if ($this ->uql_connection_handle)
            mysql_close($this -> connection_handle);

        $this ->uql_connection_handle = false;
    }

    public function freeResources()
    {
        $this -> closeConnection();
        unset($this ->uql_database_host);
        unset($this ->uql_database_name);
        unset($this ->uql_database_user_name);
        unset($this ->uql_database_password);
        unset($this ->uql_operations_charset);
        unset($this ->uql_connection_handle);
        unset($this ->uql_error_message);
    }

    public function __destruct() {
        //Clean up
        $this -> closeConnection();
        $this ->uql_database_host = null;
        $this ->uql_database_name = null;
        $this ->uql_database_user_name = null;
        $this ->uql_database_password = null;
        $this ->uql_operations_charset = null;
        $this ->uql_connection_handle = null;
        $this ->uql_error_message = null;
    }

}
?>