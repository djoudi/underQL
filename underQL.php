<?php

require_once('UQL.php');
require_once('UQLBase.php');
require_once('UQLConnection.php');
require_once('UQLMap.php');
require_once('UQLAbstractEntity.php');
require_once('UQLFilter.php');
require_once('UQLFilterEngine.php');
require_once('UQLRule.php');
require_once('UQLRuleEngine.php');
require_once('UQLQuery.php');
require_once('UQLQueryPath.php');
require_once('UQLChangeQuery.php');
require_once('UQLDeleteQuery.php');
require_once('UQLEntity.php');
require_once('UQLPlugin.php');


class underQL extends UQLBase{
    
    private $uql_database_handle;
    private $uql_entity_list; // load all table names from current database
    private $uql_loaded_entity_list;

    public function __construct(
            $host = UQL_DB_HOST,
            $database_name = UQL_DB_NAME,
            $user = UQL_DB_USER,
            $password = UQL_DB_PASSWORD,
            $charset = UQL_DB_CHARSET) {

        if(UQL_CONFIG_USE_INVOKE_CALL) {
            $php_ver = floatval(PHP_VERSION);
            if($php_ver < 5.3)
                die('underQL work at least on PHP 5.3 with invoke attribute. Go to UQL.php and change UQL_CONFIG_USE_INVOKE_CALL to false and use loadEntity method rather thatn $_(\'table\') method');
        }
        $this->uql_database_handle = new UQLConnection($host, $database_name, $user, $password, $charset);
        $this->entityListInit();
        $this->uql_loaded_entity_list = array();
    }

    public function getDatabase()
    {
        return $this->uql_database_handle;
    }

    protected function entityListInit() {
        
        $local_string_query = sprintf("SHOW TABLES FROM `%s`", $this ->uql_database_handle->getDatabaseName());
        $local_query_result = mysql_query($local_string_query/*, $this->uql_database_handle -> getConnectionHandle()*/);
        if ($local_query_result) {
            $tables_count = mysql_num_rows($local_query_result);

            while ($local_entity = mysql_fetch_row($local_query_result)) {
                $this->uql_entity_list[] = $local_entity[0];
            }

            @mysql_free_result($local_query_result);

        } else {
            die(mysql_error(/*$this->uql_database_handle -> getConnectionHandle()*/));
        }
    }

    public function loadEntity($entity_name) {

        if(strcmp($entity_name,'*') == 0)
        {
          $this->loadAllEntities();
          return;
        }

        if(!in_array($entity_name,$this->uql_entity_list))
            die($entity_name.' is not a valid table name');

        if(in_array($entity_name,$this->uql_loaded_entity_list))
            return; // no action NOP

        $GLOBALS[sprintf(UQL_ENTITY_OBJECT_SYNTAX,$entity_name)]=
                        new UQLEntity($entity_name,$this->uql_database_handle);
    }

    public function loadAllEntities()
    {
        $entity_count = @count ($this->uql_entity_list);
        for($i = 0; $i < $entity_count; $i++)
         $this->loadEntity($this->uql_entity_list[$i]);
    }

    public function __invoke($entity_name) {
        $this->loadEntity($entity_name);
    }

    public function freeResources()
    {
      $this->uql_database_handle->freeResources();
      unset($this->uql_entity_list);
      unset($this->uql_loaded_entity_list);
    }

    public function __destruct() {
         $this->freeResources();
        //$this->uql_database_handle->closeConnection();
        $this->uql_database_handle = null;
        $this->uql_entity_list = null;
        $this->uql_loaded_entity_list = null;
    }
}

$_ = new underQL();

?>