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
require_once('utilities.php');

class underQL extends UQLBase{
    
    private $uql_database_handle;
    private $uql_entity_list; // load all tables' names from current database
    private $uql_loaded_entity_list;

    public function __construct(
            $host = UQL_DB_HOST,
            $database_name = UQL_DB_NAME,
            $user = UQL_DB_USER,
            $password = UQL_DB_PASSWORD,
            $charset = UQL_DB_CHARSET) {

        /* check if we could use __invoke method syntax with underQL object or not */
        if(UQL_CONFIG_USE_INVOKE_CALL) {
            $php_ver = floatval(PHP_VERSION);
            if($php_ver < 5.3)
                $this->error('underQL work at least on PHP 5.3 to run invoke magic method. Go to UQL.php and change UQL_CONFIG_USE_INVOKE_CALL to false, after that, use loadEntity method rather thatn $_(\'table\') method');
        }
        
        $this->uql_database_handle = new UQLConnection($host, $database_name, $user, $password, $charset);
        $this->the_uql_entity_list_init();
        $this->uql_loaded_entity_list = array();
    }

    public function the_uql_get_database()
    {
        return $this->uql_database_handle;
    }

    /* read all tables(entities) from current database and store them into array */
    protected function the_uql_entity_list_init() {
        
        $local_string_query = sprintf("SHOW TABLES FROM `%s`", $this ->uql_database_handle->the_uql_get_database_name());
        $local_query_result = mysql_query($local_string_query/*, $this->uql_database_handle -> getConnectionHandle()*/);
        if ($local_query_result) {
            $tables_count = mysql_num_rows($local_query_result);

            while ($local_entity = mysql_fetch_row($local_query_result)) {
                $this->uql_entity_list[] = $local_entity[0];
            }

            @mysql_free_result($local_query_result);

        } else {
            $this->the_uql_error(mysql_error(/*$this->uql_database_handle -> getConnectionHandle()*/));
        }
    }

    /* create UQLEntity object and load all information about
       $entity_name table within it */
    public function the_uql_load_entity($entity_name) {

        if(strcmp($entity_name,'*') == 0)
        {
          $this->the_uql_load_all_entities();
          return;
        } 

        if(!in_array($entity_name,$this->uql_entity_list))
            $this->the_uql_error($entity_name.' is not a valid table name');

        if(in_array($entity_name,$this->uql_loaded_entity_list))
            return; // no action

        /* Create a global entity object. This part helps underQL to know
           the entity's object name for any loaded entity(table), therefore, underQL 
           could automatically use it in its own operations. */
           
        sprintf(UQL_ENTITY_OBJECT_SYNTAX,$entity_name);
        $GLOBALS[sprintf(UQL_ENTITY_OBJECT_SYNTAX,$entity_name)]=
                        new UQLEntity($entity_name,$this->uql_database_handle);
        
    }

    /* You can load all tables as objects at once by use * symbol. This function
       used to do that. */
    public function the_uql_load_all_entities()
    {
        $entity_count = @count ($this->uql_entity_list);
        for($i = 0; $i < $entity_count; $i++)
         $this->the_uql_load_entity($this->uql_entity_list[$i]);
    }

    /* Helps underQL to use (object as function) syntax. However, this method used
       only with PHP 5.3.x and over */
    public function __invoke($entity_name) {
        $this->the_uql_load_entity($entity_name);
    }

    public function __destruct() {
        $this->uql_database_handle = null;
        $this->uql_entity_list = null;
        $this->uql_loaded_entity_list = null;
    }
}

 /* Create underQL (this object called 'under') object. This is the default object, but
    you can create another instance if you would like to deal with another database
    by specifying the parameters for that database. However, you can change the name
    of the ($_) 'under' object but, it is unpreferable(might be for future purposes).
 */
$_ = new underQL();

?>