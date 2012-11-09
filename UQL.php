<?php


define('UQL_VERSION','1.0.0');
define('UQL_VERSION_ID',20120512);

//define('UQL_VERSION_CODE_NAME','Eid');
define('UQL_DIR_FILTER','filters/');
define('UQL_DIR_FILTER_API','filters_api/');
//%s represents the table name
//define ('UQL_ABSTRACT_E_OBJECT_SYNTAX','the_%s_abstract');

define('UQL_FILTER_IN', 0xA);
define('UQL_FILTER_OUT',0xC);

//%s represents the table name
define ('UQL_FILTER_OBJECT_SYNTAX','%s_filter');
define ('UQL_FILTER_FUNCTION_NAME','ufilter_%s');

//%s represents the table name
define ('UQL_RULE_OBJECT_SYNTAX','%s_rule');
define ('UQL_RULE_FUNCTION_NAME','urule_%s');

define ('UQL_RULE_SUCCESS',0x0D);

define ('UQL_ENTITY_OBJECT_SYNTAX','%s');

/* Database connection information */
define ('UQL_DB_HOST','localhost');
define ('UQL_DB_USER','root');
define ('UQL_DB_PASSWORD','root');
define ('UQL_DB_NAME','abdullaheid_db');
define ('UQL_DB_CHARSET','utf8');

define ('UQL_CONFIG_USE_INVOKE_CALL',true); // to use __invoke magic method
?>