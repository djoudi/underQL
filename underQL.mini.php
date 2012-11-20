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

define ( 'UQL_VERSION', '1.0.0' );
define ( 'UQL_VERSION_ID', 20120512 );

//define('UQL_VERSION_CODE_NAME','Eid');
define ( 'UQL_DIR_FILTER', 'filters/' );
define ( 'UQL_DIR_FILTER_API', 'filters_api/' );

define ( 'UQL_DIR_RULE', 'rules/' );
define ( 'UQL_DIR_RULE_API', 'rules_api/' );
//%s represents the table name
//define ('UQL_ABSTRACT_E_OBJECT_SYNTAX','the_%s_abstract');


define ( 'UQL_FILTER_IN', 0xA );
define ( 'UQL_FILTER_OUT', 0xC );

//%s represents the table name
define ( 'UQL_FILTER_OBJECT_SYNTAX', '%s_filter' );
define ( 'UQL_FILTER_FUNCTION_NAME', 'ufilter_%s' );

//%s represents the table name
define ( 'UQL_RULE_OBJECT_SYNTAX', '%s_rule' );
define ( 'UQL_RULE_FUNCTION_NAME', 'urule_%s' );

define ( 'UQL_RULE_SUCCESS', 0x0D );

define ( 'UQL_ENTITY_OBJECT_SYNTAX', '%s' );

/* Database connection information */
define ( 'UQL_DB_HOST', 'localhost' );
define ( 'UQL_DB_USER', 'root' );
define ( 'UQL_DB_PASSWORD', 'root' );
define ( 'UQL_DB_NAME', 'abdullaheid_db' );
define ( 'UQL_DB_CHARSET', 'utf8' );

define ( 'UQL_CONFIG_USE_INVOKE_CALL', true ); // to use __invoke magic method


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

class UQLMap extends UQLBase {
	
	private $uql_map_list;
	private $uql_elements_count;
	
	public function __construct() {
		$this->uql_map_list = array ();
		$this->uql_elements_count = 0;
	}
	
	public function the_uql_add_element($key, $value) {
		
		if ($this->the_uql_find_element ( $key ) == null)
			$this->uql_elements_count ++;
		
		$this->uql_map_list [$key] = $value;
	}
	
	public function the_uql_find_element($key) {
		if ($this->the_uql_is_element_exist ( $key ))
			return $this->uql_map_list [$key];
		
		return null;
	}
	
	public function the_uql_is_element_exist($key) {
		if ($this->uql_elements_count <= 0)
			return false;
		
		if (@array_key_exists ( $key, $this->uql_map_list ))
			return true;
		
		return false;
	}
	
	public function the_uql_get_count() {
		return count ( $this->uql_map_list );
	}
	
	public function the_uql_remove_element($key) {
		
		if ($this->the_uql_is_element_exist ( $key )) {
			unset ( $this->uql_map_list [$key] );
			$this->uql_elements_count --;
		}
	}
	
	public function the_uql_is_empty() {
		return $this->uql_elements_count == 0;
	}
	
	public function mapCallback($callback) {
		if (! $this->isEmpty ())
			return array_map ( $callback, $this->map_list );
	}
	
	public function the_uql_get_map() {
		return $this->uql_map_list;
	}
	
	public function __destruct() {
		
		$this->uql_map_list = null;
		$this->uql_elements_count = 0;
	}

}

class UQLAbstractEntity extends UQLBase {
	
	private $uql_entity_name;
	private $uql_fields;
	private $uql_fields_count;
	
	public function __construct($entity_name, &$database_handle) {
		
		$this->uql_entity_name = null;
		$this->uql_fields = null;
		$this->uql_fields_count = 0;
		
		$this->the_uql_set_entity_name ( $entity_name, $database_handle );
	}
	
	public function the_uql_set_entity_name($entity_name, &$database_handle) {
		if (($database_handle instanceof UQLConnection)) {
			$this->uql_entity_name = $entity_name;
			$local_string_query = sprintf ( "SHOW COLUMNS FROM `%s`", $this->uql_entity_name );
			$local_query_result = mysql_query ( $local_string_query/*, $database_handle -> getConnectionHandle()*/);
			if ($local_query_result) {
				$this->uql_fields_count = mysql_num_rows ( $local_query_result );
				@mysql_free_result ( $local_query_result );
				
				$local_fields_list = mysql_list_fields ( $database_handle->the_uql_get_database_name (), $this->uql_entity_name );
				$this->uql_fields = array ();
				
				$local_i = 0;
				while ( $local_i < $this->uql_fields_count ) {
					$local_field = mysql_fetch_field ( $local_fields_list );
					$this->uql_fields [$local_field->name] = $local_field;
					$local_i ++;
				}
				
				@mysql_free_result ( $local_fields_list );
			} else {
				$this->error ( mysql_error(/*$database_handle -> getConnectionHandle()*/) );
			}
		}
	}
	
	public function the_uql_get_entity_name() {
		return $this->uql_entity_name;
	}
	
	public function the_uql_is_field_exist($name) {
		return (($this->uql_fields != null) && (array_key_exists ( $name, $this->uql_fields )));
	}
	
	public function the_uql_get_field_object($name) {
		if ($this->the_uql_is_field_exist ( $name ))
			return $this->uql_fields [$name];
		return null;
	}
	
	public function the_uql_get_all_fields() {
		return $this->uql_fields;
	}
	
	public function the_uql_get_fields_count() {
		return $this->uql_fields_count;
	}
	
	public function __destruct() {
		$this->uql_entity_name = null;
		$this->uql_fields = null;
		$this->uql_fields_count = 0;
	}

}

class UQLFilter extends UQLBase {
	
	private $uql_entity_name;
	private $uql_filters_map;
	
	public function __construct($entity_name) {
		$this->uql_entity_name = $entity_name;
		$this->uql_filters_map = new UQLMap ();
	}
	
	public function __call($function_name, $parameters) {
		$local_params_count = count ( $parameters );
		if ($local_params_count < 1 /*filter_name [AT LEAST]*/)
            $this->the_uql_error ( $function_name . ' filter must have 1 parameter at least' );
		
		if ($local_params_count == 1)
			$this->the_uql_add_filter ( $function_name, array ($parameters [0], 'inout' ) );
		else
			$this->the_uql_add_filter ( $function_name, $parameters );
		
		return $this;
	}
	
	protected function the_uql_add_filter($field, $filter) {
		if (! $this->uql_filters_map->the_uql_is_element_exist ( $field ))
			$this->uql_filters_map->the_uql_add_element ( $field, new UQLMap () );
		
		$local_filter = $this->uql_filters_map->the_uql_find_element ( $field );
		$local_filter->the_uql_add_element ( $local_filter->the_uql_get_count (), array ('filter' => $filter, 'is_active' => true ) );
		$this->uql_filters_map->the_uql_add_element ( $field, $local_filter );
	}
	
	protected function the_uql_set_filter_activation($field_name, $filter_name, $activation) {
		$local_filter = $this->uql_filters_map->the_uql_find_element ( $field_name );
		if (! $local_filter)
			$this->the_uql_error ( 'You can not stop a filter for unknown field (' . $field_name . ')' );
			
		/* $target_filter = $local_filter->the_uql_find_element($filter_name);
        if(!$target_filter)
            $this->the_uql_error('You can not stop unknown filter ('.$filter_name.')'); */
		
		for($i = 0; $i < $local_filter->the_uql_get_count (); $i ++) {
			$target_filter = $local_filter->the_uql_find_element ( $i );
			if (strcmp ( $target_filter ['filter'] [0], $filter_name ) == 0) {
				$target_filter ['is_active'] = $activation;
				$local_filter->the_uql_add_element ( $i, array ('filter' => $target_filter ['filter'], 'is_active' => $activation ) );
				$this->uql_filters_map->the_uql_add_element ( $field_name, $local_filter );
			}
		}
	
	}
	
	public function the_uql_start_filters(/*$field_name,$filter_name*/)
    {
		$params_count = func_num_args ();
		if ($params_count < 2)
			$this->the_uql_error ( 'start_filters needs 2 parameters at least' );
		
		$filters_counts = $params_count - 1; // remove field name
		$parameters = func_get_args ();
		if ($filters_counts == 1) {
			$this->the_uql_set_filter_activation ( $parameters [0], $parameters [1], true );
			return;
		} else {
			for($i = 0; $i < $filters_counts - 1; $i ++)
				$this->the_uql_set_filter_activation ( $parameters [0], $parameters [$i + 1], true );
		}
	}
	
	public function the_uql_stop_filters(/*$field_name,$filter_name*/)
    {
		$params_count = func_num_args ();
		if ($params_count < 2)
			$this->the_uql_error ( 'stop_filters needs 2 parameters at least' );
		
		$filters_counts = $params_count - 1; // remove field name
		$parameters = func_get_args ();
		if ($filters_counts == 1) {
			$this->the_uql_set_filter_activation ( $parameters [0], $parameters [1], false );
			return;
		} else {
			for($i = 0; $i < $filters_counts - 1; $i ++)
				$this->the_uql_set_filter_activation ( $parameters [0], $parameters [$i + 1], false );
		}
	}
	
	public function the_uql_get_filters_by_field_name($field_name) {
		return $this->uql_filters_map->the_uql_find_element ( $field_name );
	}
	
	public function the_uql_get_filters() {
		return $this->uql_filters_map;
	}
	
	public function the_uql_get_entity_name() {
		return $this->uql_entity_name;
	}
	
	public static function the_uql_find_filter_object($entity) {
		$filter_object_name = sprintf ( UQL_FILTER_OBJECT_SYNTAX, $entity );
		if (isset ( $GLOBALS [$filter_object_name] ))
			$filter_object = $GLOBALS [$filter_object_name];
		else
			$filter_object = null;
		
		return $filter_object;
	}
	
	public function __destruct() {
		$this->uql_entity_name = null;
		$this->uql_filters_map = null;
	}
}

class UQLFilterEngine extends UQLBase {
	
	private $uql_filter_object;
	private $uql_values_map; //current inserted | updated $key => $value pairs
	private $uql_in_out_flag; // specify if the engine for input or output
	

	public function __construct(&$filter_object, $in_out_flag) {
		$this->uql_filter_object = $filter_object;
		$this->uql_values_map = null;
		$this->uql_in_out_flag = $in_out_flag;
	}
	
	public function the_uql_set_values_map(&$values_map) {
		$this->uql_values_map = $values_map;
	}
	
	public function the_uql_apply_filter($field_name, $value) {
		if ($this->uql_filter_object != null)
			$filters = $this->uql_filter_object->the_uql_get_filters_by_field_name ( $field_name );
		else
			return $value;
		
		if ($filters == null)
			return $value;
		
		$tmp_value = $value;
		
		foreach ( $filters->the_uql_get_map () as $filter_id => $filter_value ) {
			$filter_name = $filter_value ['filter'] [0];
			$filter_flag = $filter_value ['filter'] [1];
			// echo $filter_flag;
			if (strcmp ( strtolower ( $filter_flag ), 'in' ) == 0)
				$filter_flag = UQL_FILTER_IN;
			else if (strcmp ( strtolower ( $filter_flag ), 'out' ) == 0)
				$filter_flag = UQL_FILTER_OUT;
			else
				$filter_flag = UQL_FILTER_IN | UQL_FILTER_OUT;
			
			if ((! $filter_value ['is_active']) || (($filter_flag != $this->uql_in_out_flag) && ($filter_flag != UQL_FILTER_IN | UQL_FILTER_OUT)))
				continue;
			
			$include_filter_api = 'include_filters';
			$include_filter_api ( $filter_name );
			
			$filter_api_function = sprintf ( UQL_FILTER_FUNCTION_NAME, $filter_name );
			
			if (! function_exists ( $filter_api_function ))
				die ( $filter_name . ' is not a valid filter' );
			
			if (@count ( $filter_value ['filter'] ) == 2) // the filter has no parameter(s)
				$tmp_value = $filter_api_function ( $field_name, $tmp_value, $filter_flag );
			else {
				$params = array_slice ( $filter_value ['filter'], 2 );
				$tmp_value = $filter_api_function ( $field_name, $tmp_value, $filter_flag, $params );
			}
		}
		return $tmp_value;
	}
	
	public function the_uql_run_engine() {
		if (! $this->uql_values_map || $this->uql_values_map->the_uql_get_count () == 0)
			return null;
		
		foreach ( $this->uql_values_map->the_uql_get_map () as $name => $value ) {
			$this->uql_values_map->the_uql_add_element ( $name, $this->the_uql_apply_filter ( $name, $value ) );
		}
		return $this->uql_values_map;
	}
	
	public function __destruct() {
		$this->uql_values_map = null;
		$this->uql_filter_object = null;
	}
}

class UQLRule extends UQLBase {
	
	private $uql_entity_name;
	private $uql_alises_map;
	private $uql_rules_map;
	
	public function __construct($entity_name) {
		
		$this->uql_entity_name = $entity_name;
		$this->uql_alises_map = new UQLMap ();
		$this->uql_rules_map = new UQLMap ();
	}
	
	public function __call($function_name, $parameters) {
		
		$local_params_count = count ( $parameters );
		if ($local_params_count == 0)
			return;
		
		$this->the_uql_add_rule ( $function_name, $parameters );
		return $this;
	}
	
	protected function the_uql_add_rule($field, $rule) {
		
		if (! $this->uql_rules_map->the_uql_is_element_exist ( $field ))
			$this->uql_rules_map->the_uql_add_element ( $field, new UQLMap () );
		
		$local_rule = $this->uql_rules_map->the_uql_find_element ( $field );
		$local_rule->the_uql_add_element ( $local_rule->the_uql_get_count (), array ('rule' => $rule, 'is_active' => true ) );
		
		$this->uql_rules_map->the_uql_add_element ( $field, $local_rule );
	}
	
	protected function the_uql_set_rule_activation($field_name, $rule_name, $activation) {
		$local_rule = $this->uql_rules_map->the_uql_find_element ( $field_name );
		
		if (! $local_rule)
			$this->the_uql_error ( 'You can not stop a rule for unknown field (' . $field_name . ')' );
			
		/*$target_rule = $local_rule->the_uql_find_element($rule_name);
        if(!$target_rule)
            $this->the_uql_error('You can not stop unknown rule ('.$rule_name.')');*/
		
		for($i = 0; $i < $local_rule->the_uql_get_count (); $i ++) {
			$target_rule = $local_rule->the_uql_find_element ( $i );
			if (strcmp ( $target_rule ['rule'] [0], $rule_name ) == 0) {
				$target_rule ['is_active'] = $activation;
				$local_rule->the_uql_add_element ( $i, array ('rule' => $target_rule ['rule'], 'is_active' => $activation ) );
				$this->uql_rules_map->the_uql_add_element ( $field_name, $local_rule );
			}
		}
	
	}
	
	public function the_uql_start_rules(/*$field_name,$rule_name*/)
    {
		$params_count = func_num_args ();
		if ($params_count < 2)
			$this->error ( 'start_rules needs 2 parameters at least' );
		
		$rules_counts = $params_count - 1; // remove field name
		$parameters = func_get_args ();
		if ($rules_counts == 1) {
			$this->the_uql_set_rule_activation ( $parameters [0], $parameters [1], true );
			return;
		} else {
			for($i = 0; $i < $rules_counts - 1; $i ++)
				$this->the_uql_set_rule_activation ( $parameters [0], $parameters [$i + 1], true );
		}
	}
	
	public function the_uql_stop_rules(/*$field_name,$rule_name*/)
    {
		$params_count = func_num_args ();
		if ($params_count < 2)
			$this->the_uql_error ( 'stop_rules needs 2 parameters at least' );
		
		$rules_counts = $params_count - 1; // remove field name
		$parameters = func_get_args ();
		if ($rules_counts == 1) {
			$this->the_uql_set_rule_activation ( $parameters [0], $parameters [1], false );
			return;
		} else {
			for($i = 0; $i < $rules_counts - 1; $i ++)
				$this->the_uql_set_rule_activation ( $parameters [0], $parameters [$i + 1], false );
		}
	}
	
	public function the_uql_get_rules_by_field_name($field_name) {
		
		return $this->uql_rules_map->the_uql_find_element ( $field_name );
	}
	
	public function the_uql_add_alias($key, $value) {
		
		$this->uql_alises_map->the_uql_add_element ( $key, $value );
	}
	
	public function the_uql_get_alias($key) {
		
		return $this->uql_alises_map->the_uql_find_element ( $key );
	}
	
	public function the_uql_get_rules() {
		return $this->uql_alises_map;
	}
	
	public function the_uql_get_entity_name() {
		return $this->uql_entity_name;
	}
	
	public function the_uql_get_aliases() {
		return $this->uql_alises_map;
	}
	
	public static function the_uql_find_rule_object($entity) {
		
		$rule_object_name = sprintf ( UQL_RULE_OBJECT_SYNTAX, $entity );
		
		if (isset ( $GLOBALS [$rule_object_name] ))
			$rule_object = $GLOBALS [$rule_object_name];
		else
			$rule_object = null;
		
		return $rule_object;
	
	}
	
	public function __destruct() {
		
		$this->uql_entity_name = null;
		$this->uql_rules_map = null;
		$this->uql_alises_map = null;
	}
}

class UQLRuleEngine extends UQLBase {
	
	private $uql_rule_object;
	private $uql_values_map; //current inserted | updated $key => $value pairs
	private $uql_false_rule_flag; // true if there is at least one rule failed.
	private $uql_fail_rules_list; // list of error messages about each field that fail in one or more rules
	

	public function __construct(&$rule_object, &$values_map) {
		
		$this->uql_rule_object = $rule_object;
		$this->uql_values_map = $values_map;
		$this->uql_false_rule_flag = false;
		$this->uql_fail_rules_list = new UQLMap ();
	}
	
	protected function the_uql_apply_rule($field_name, $value) {
		
		$rules = $this->uql_rule_object->the_uql_get_rules_by_field_name ( $field_name );
		
		$the_results = array ();
		
		if ($rules == null)
			return true;
		
		foreach ( $rules->the_uql_get_map () as $rule_id => $rule_value ) {
			
			if (! $rule_value ['is_active'])
				continue;
			
			$rule_name = $rule_value ['rule'] [0];
			$include_rule_api = 'include_rules';
			$include_rule_api ( $rule_name );
			
			$rule_api_function = sprintf ( UQL_RULE_FUNCTION_NAME, $rule_name );
			
			if (! function_exists ( $rule_api_function ))
				$this->the_uql_error ( $rule_name . ' is not a valid rule' );
			
			$alias = $this->uql_rule_object->the_uql_get_alias ( $field_name );
			
			if (@count ( $rule_value ['rule'] ) == 1) // the rule has no parameter(s)
				$result = $rule_api_function ( $field_name, $value, $alias );
			else {
				$params = array_alice ( $rule_value ['rule'] ); // remove rule name
				$result = $rule_api_function ( $field_name, $value, $alias, $params );
			}
			
			if ($result != UQL_RULE_SUCCESS) {
				$the_results [$rule_name] = $result; // message
				$this->uql_false_rule_flag = true;
			} else
				$the_results [$rule_name] = $result; // OK
		}
		
		return $the_results;
	}
	
	public function the_uql_are_rules_passed() {
		return $this->uql_false_rule_flag == false;
	}
	
	public function the_uql_run_engine() {
		
		if (! $this->uql_values_map || $this->uql_values_map->the_uql_get_count () == 0)
			return null;
		
		$result = true;
		
		foreach ( $this->uql_values_map->the_uql_get_map () as $name => $value ) {
			
			$result = $this->the_uql_apply_rule ( $name, $value );
			
			if ($result != UQL_RULE_SUCCESS)
				$this->uql_fail_rules_list->the_uql_add_element ( $name, $result );
		}
		
		if ($this->the_uql_are_rules_passed ())
			return true;
		
		return $this->uql_fail_rules_list->the_uql_get_map ();
	}
	
	public function __destruct() {
		$this->uql_values_map = null;
		$this->uql_rule_object = null;
	}
}

class UQLQuery extends UQLBase {
	
	private $uql_database_handle;
	private $uql_query_result;
	private $uql_current_row_object;
	private $uql_current_query_fields;
	
	public function __construct(&$database_handle) {
		$this->uql_database_handle = (($database_handle instanceof UQLConnection) ? $database_handle : null);
		$this->uql_query_result = null;
		$this->uql_current_row_object = null;
		$this->uql_current_query_fields = array ();
	}
	
	public function the_uql_set_database_handle($database_handle) {
		$this->database_handle ( ($database_handle instanceof UQLConnection) ? $database_handle : null );
	}
	
	public function the_uql_get_database_handle() {
		return $this->uql_database_handle;
	}
	
	public function the_uql_execute_query($query) {
		if ($this->uql_database_handle instanceof UQLConnection) {
			$this->uql_query_result = mysql_query ( $query /*,$this -> database_handle*/);
			
			$this->the_uql_is_there_any_error ();
			
			if (! $this->uql_query_result)
				return false;
			
			return true;
		}
		
		return false;
	}
	
	public function the_uql_get_current_query_fields() {
		if (! $this->uql_query_result)
			return null;
		
		$local_fields_count = @mysql_num_fields ( $this->uql_query_result );
		if ($local_fields_count == 0)
			return null;
		
		for($local_i = 0; $local_i < $local_fields_count; $local_i ++)
			$this->uql_current_query_fields [$local_i] = mysql_field_name ( $this->uql_query_result, $local_i );
		
		return $this->uql_current_query_fields;
	}
	
	public function the_uql_fetch_row() {
		if ($this->uql_query_result) {
			$this->uql_current_row_object = mysql_fetch_object ( $this->uql_query_result );
			return $this->uql_current_row_object;
		}
		
		return false;
	}
	
	public function the_uql_reset_result() {
		if ($this->uql_query_result)
			return mysql_data_seek ( $this->uql_query_result, 0 );
		
		return false;
	}
	
	public function the_uql_get_current_row() {
		return $this->uql_current_row_object;
	}
	
	public function the_uql_get_count() {
		if ($this->uql_query_result)
			return mysql_num_rows ( $this->uql_query_result );
		
		return 0;
	}
	
	public function the_uql_get_affected_rows() {
		if (($this->uql_database_handle instanceof UQLConnection) && ($this->uql_query_result))
			return mysql_affected_rows ( $this->uql_database_handle );
		
		return 0;
	}
	
	public function the_uql_get_last_inserted_id() {
		if (($this->uql_database_handle instanceof UQLConnection) && ($this->uql_query_result))
			return mysql_insert_id ( $this->uql_database_handle );
		
		return 0;
	}
	
	public function the_uql_free_result() {
		if ($this->uql_query_result)
			@mysql_free_result ( $this->uql_query_result );
		
		$this->uql_current_row_object = null;
		$this->uql_query_result = null;
		$this->uql_current_query_fields = array ();
	}
	
	public function the_uql_is_there_any_error() {
		if (mysql_errno () != 0)
			$this->the_uql_error ( '[MySQL query error - ' . mysql_errno () . '] - ' . mysql_error () );
	}
	
	public function __destruct() {
		$this->the_uql_free_result ();
		$this->uql_query_result = null;
		$this->uql_current_query_fields = null;
		$this->uql_current_row_object = null;
		$this->uql_database_handle = null;
	}

}

class UQLQueryPath extends UQLBase {
	
	public $uql_abstract_entity;
	// reference to the abstract table's data
	public $uql_query_object;
	public $uql_filter_engine;
	
	public function __construct(&$database_handle, &$abstract_entity) {
		
		if ($abstract_entity instanceof UQLAbstractEntity)
			$this->uql_abstract_entity = $abstract_entity;
		else
			$this->error ( 'You must provide a appropriate value for abstract_entity parameter' );
		
		$this->uql_query_object = new UQLQuery ( $database_handle );
		$filter_object = UQLFilter::the_uql_find_filter_object ( $this->uql_abstract_entity->the_uql_get_entity_name () );
		$this->uql_filter_engine = new UQLFilterEngine ( $filter_object, UQL_FILTER_OUT );
	}
	
	public function the_uql_execute_query($query) {
		
		if ($this->uql_query_object->the_uql_execute_query ( $query )) {
			if ($this->uql_query_object->the_uql_get_count () > 0) {
				$this->the_uql_get_next ();
				return true;
			}
		}
		
		return false;
	
	}
	
	public function the_uql_get_next() {
		return $this->uql_query_object->the_uql_fetch_row ();
	}
	
	public function the_uql_get_count() {
		return $this->uql_query_object->the_uql_get_count ();
	}
	
	public function the_uql_get_query_object() {
		return $this->uql_query_object;
	}
	
	public function the_uql_get_abstract_entity() {
		return $this->uql_abstract_entity;
	}
	
	public function __get($key) {
		
		if (! $this->uql_abstract_entity->the_uql_is_field_exist ( $key ))
			$this->the_uql_error ( "Unknown field [$key]" );
		
		$local_current_query_fields = $this->uql_query_object->the_uql_get_current_query_fields ();
		if ($local_current_query_fields == null)
			return "Unknown";
		
		foreach ( $local_current_query_fields as $local_field_name ) {
			if (strcmp ( $key, $local_field_name ) == 0) {
				$local_current_row = $this->uql_query_object->the_uql_get_current_row ();
				if ($local_current_row == null)
					return "Unknown";
				else {
					return $this->uql_filter_engine->the_uql_apply_filter ( $key, $local_current_row->$key );
				}
			}
		}
		
		return "Unknown";
	}
	
	public function __destruct() {
		
		$this->uql_abstract_entity = null;
		$this->uql_query_object = null;
	
		//$this->plugin = null;
	}

}

class UQLChangeQuery extends UQLBase {
	
	private $uql_the_query;
	private $uql_the_abstract_entity;
	/*
	used to save list of [field_name = value] that are coming
	 from current insertion query
    */
	private $uql_the_values_map;
	private $uql_the_rule_engine;
	private $uql_the_rule_engine_results;
	
	public function __construct(&$database_handle, &$abstract_entity) {
		if ((! $database_handle instanceof UQLConnection) || (! $abstract_entity instanceof UQLAbstractEntity))
			$this->the_uql_error ( 'Bad database handle' );
		
		$this->uql_the_query = new UQLQuery ( $database_handle );
		$this->uql_the_abstract_entity = $abstract_entity;
		$this->uql_the_values_map = new UQLMap ();
		$this->uql_the_rule_engine = null;
		$this->uql_the_rule_engine_results = null;
	}
	
	public function __set($name, $value) {
		if (! $this->uql_the_abstract_entity->the_uql_is_field_exist ( $name ))
			$this->the_uql_error ( $name . ' is not a valid column name' );
		
		$this->uql_the_values_map->the_uql_add_element ( $name, $value );
	
		//echo '<pre>'; var_dump($this->uql_the_values_map); echo '</pre>';
	}
	
	public function __get($name) { //echo $name;
		//echo '<pre>'; var_dump($this->uql_the_abstract_entity); echo '</pre>';
		if (! $this->uql_the_abstract_entity->isFieldExist ( $name ))
			$this->the_uql_error ( $name . ' is not a valid column name' );
		
		if (! $this->uql_the_values_map->the_uql_is_element_exist ( $name ))
			return null;
		else
			return $this->uql_the_values_map->the_uql_find_element ( $name );
	
	}
	
	public function the_uql_are_rules_passed() {
		if ($this->uql_the_rule_engine != null)
			return $this->uql_the_rule_engine->the_uql_are_rules_passed ();
		
		return true;
	}
	
	public function the_uql_get_messages_list() {
		if (($this->uql_the_rule_engine != null) || ($this->uql_the_rule_engine_results == true))
			return $this->uql_the_rule_engine_results;
		
		return null;
	
	}
	
	protected function the_uql_format_insert_query() {
		$values_count = $this->uql_the_values_map->the_uql_get_count ();
		if ($values_count == 0)
			return "";
		
		$insert_query = 'INSERT INTO `' . $this->uql_the_abstract_entity->the_uql_get_entity_name () . '` (';
		
		$fields = '';
		$values = 'VALUES(';
		
		$all_values = $this->uql_the_values_map->the_uql_get_map ();
		$comma = 0; // for last comma in a string
		

		foreach ( $all_values as $key => $value ) {
			$fields .= "`$key`";
			$field_object = $this->uql_the_abstract_entity->the_uql_get_field_object ( $key );
			if ($field_object->numeric)
				$values .= $value;
			else // string quote
				$values .= "'$value'";
			
			$comma ++;
			
			if (($comma) < $values_count) {
				$fields .= ',';
				$values .= ',';
			}
		}
		
		$values .= ')';
		
		$insert_query .= $fields . ') ' . $values;
		return $insert_query;
	}
	
	protected function the_uql_insert_or_update($is_save = true, $extra = '') {
		$values_count = $this->uql_the_values_map->the_uql_get_count ();
		if ($values_count == 0)
			return false;
		
		$rule_object = UQLRule::the_uql_find_rule_object ( $this->uql_the_abstract_entity->the_uql_get_entity_name () );
		
		if ($rule_object != null) {
			$this->uql_the_rule_engine = new UQLRuleEngine ( $rule_object, $this->uql_the_values_map );
			
			$this->uql_the_rule_engine_results = $this->uql_the_rule_engine->the_uql_run_engine ();
			
			if (! $this->uql_the_rule_engine->the_uql_are_rules_passed ())
				return false;
		}
		
		$filter_object = UQLFilter::the_uql_find_filter_object ( $this->uql_the_abstract_entity->the_uql_get_entity_name () );
		
		if ($filter_object != null) {
			$fengine = new UQLFilterEngine ( $filter_object, UQL_FILTER_IN );
			$fengine->the_uql_set_values_map ( $this->uql_the_values_map );
			$this->uql_the_values_map = $fengine->the_uql_run_engine ();
		}
		
		if ($is_save)
			$query = $this->the_uql_format_insert_query ();
		else
			$query = $this->the_uql_format_update_query ( $extra );
		
		// clear values
		$this->uql_the_values_map = new UQLMap ();
		
		return $this->uql_the_query->the_uql_execute_query ( $query );
	}
	
	public function the_uql_insert() {
		return $this->the_uql_insert_or_update ();
	}
	
	protected function the_uql_format_update_query($extra = '') {
		$values_count = $this->uql_the_values_map->the_uql_get_count ();
		if ($values_count == 0)
			return "";
		
		$update_query = 'UPDATE `' . $this->uql_the_abstract_entity->the_uql_get_entity_name () . '` SET ';
		
		$fields = '';
		
		$all_values = $this->uql_the_values_map->the_uql_get_map ();
		$comma = 0; // for last comma in a string
		

		foreach ( $all_values as $key => $value ) {
			$fields .= "`$key` = ";
			$field_object = $this->uql_the_abstract_entity->the_uql_get_field_object ( $key );
			if ($field_object->numeric)
				$fields .= $value;
			else // string quote
				$fields .= "'$value'";
			
			$comma ++;
			
			if (($comma) < $values_count) {
				$fields .= ',';
			}
		}
		
		$update_query .= $fields . ' ' . $extra;
		
		return $update_query;
	}
	
	public function update($extra = '') {
		return $this->insertOrUpdate ( false, $extra );
	}
	
	public function the_uql_update_where_id($id, $id_name = 'id') {
		return $this->the_uql_update ( "WHERE `$id_name` = $id" );
	}
	
	public function __destruct() {
		$this->uql_the_query = null;
		$this->uql_the_abstract_entity = null;
		$this->uql_the_values_map = null;
		$this->uql_the_rule_engine = null;
		$this->uql_the_rule_engine_results = null;
	}

}

class UQLDeleteQuery extends UQLBase {
	
	private $uql_query;
	private $uql_abstract_entity;
	
	public function __construct(&$database_handle, &$abstract_entity) {
		if ((! $database_handle instanceof UQLConnection) || (! $abstract_entity instanceof UQLAbstractEntity))
			$this->the_uql_error ( 'Bad database handle' );
		
		$this->uql_query = new UQLQuery ( $database_handle );
		$this->uql_abstract_entity = $abstract_entity;
	}
	
	protected function the_uql_format_delete_query($extra = null) {
		
		$delete_query = 'DELETE FROM `' . $this->uql_abstract_entity->the_uql_get_entity_name () . '`';
		if ($extra != null)
			$delete_query .= ' WHERE ' . $extra;
		
		return $delete_query;
	}
	
	public function the_uql_delete($extra = '') {
		$query = $this->the_uql_format_delete_query ( $extra );
		return $this->uql_query->the_uql_execute_query ( $query );
	}
	
	public function the_uql_delete_where_id($id, $id_name = 'id') {
		return $this->the_uql_delete ( "`$id_name` = $id" );
	}
	
	public function __destruct() {
		$this->uql_query = null;
		$this->uql_abstract_entity = null;
	}

}

class UQLEntity extends UQLBase {
	
	private $uql_abstract_entity;
	private $uql_database_handle;
	private $uql_path;
	private $uql_change;
	private $uql_delete;
	
	public function __construct($entity_name, &$database_handle) {
		
		$this->uql_abstract_entity = new UQLAbstractEntity ( $entity_name, $database_handle );
		$this->uql_database_handle = $database_handle;
		$this->uql_path = null;
		$this->uql_change = new UQLChangeQuery ( $database_handle, $this->uql_abstract_entity );
		$this->uql_delete = new UQLDeleteQuery ( $database_handle, $this->uql_abstract_entity );
	}
	
	public function __set($name, $value) {
		$this->uql_change->$name = $value;
		return $this;
	}
	
	public function __get($name) {
		return $this->uql_change->$name;
	}
	
	public function the_uql_insert() {
		return $this->uql_change->the_uql_insert ();
	}
	
	public function the_uql_insert_or_update_from_array($the_array, $extra = '', $is_save = true) {
		//$array_count = @count($the_array);
		foreach ( $the_array as $key => $value ) {
			if ($this->uql_abstract_entity->the_uql_is_field_exist ( $key ))
				$this->$key = $value;
		}
		
		if ($is_save)
			return $this->the_uql_insert ();
		else
			return $this->the_uql_update ( $extra );
	}
	
	public function the_uql_insert_from_array($the_array) {
		return $this->the_uql_insert_or_update_from_array ( $the_array, null );
	}
	
	public function the_uql_update_from_array($the_array, $extra = '') {
		return $this->insert_or_update_from_array ( $the_array, $extra, false );
	}
	
	public function the_uql_update_from_array_where_id($the_array, $id, $id_name = 'id') {
		return $this->insert_or_update_from_array ( $the_array, "WHERE `$id_name` = $id", false );
	}
	
	public function the_uql_update($extra = '') {
		return $this->uql_change->the_uql_update ( $extra );
	}
	
	public function the_uql_update_where_id($id, $id_name = 'id') {
		return $this->uql_change->the_uql_update_where_id ( $id, $id_name );
	}
	
	public function the_uql_delete($extra = '') {
		return $this->uql_delete->the_uql_delete ( $extra );
	}
	
	public function the_uql_delete_where_id($id, $id_name = 'id') {
		return $this->uql_delete->the_uql_delete_where_id ( $id, $id_name );
	}
	
	public function the_uql_query($query) {
		
		$this->uql_path = new UQLQueryPath ( $this->uql_database_handle, $this->uql_abstract_entity );
		if ($this->uql_path->the_uql_execute_query ( $query ))
			return $this->uql_path;
		
		return false;
	}
	
	public function the_uql_select($fields = '*', $extra = '') {
		$query = sprintf ( "SELECT %s FROM `%s` %s", $fields, $this->uql_abstract_entity->the_uql_get_entity_name (), $extra );
		
		return $this->the_uql_query ( $query );
	}
	
	public function the_uql_select_where_id($fields, $id, $id_name = 'id') {
		return $this->the_uql_select ( $fields, "WHERE `$id_name` = $id" );
	}
	
	public function the_uql_are_rules_passed() {
		return $this->uql_change->the_uql_are_rules_passed ();
	}
	
	public function the_uql_get_messages_list() {
		return $this->uql_change->the_uql_get_messages_list ();
	}
	
	public function the_uql_get_abstract_entity() {
		return $this->uql_abstract_entity;
	}
	
	public function __destruct() {
		$this->uql_abstract_entity = null;
		$this->uql_database_handle = null;
		$this->uql_path = null;
		$this->uql_change = null;
		$this->uql_delete = null;
	}

}

function include_filters() {
	$params = func_get_args ();
	
	if (func_num_args () == 0)
		die ( 'You must pass one filter at least to include_filters' );
	
	foreach ( $params as $key => $filter )
		require_once (__DIR__ . '/' . UQL_DIR_FILTER . UQL_DIR_FILTER_API . 'uql_filter_' . $filter . '.php');
}

function include_rules() {
	$params = func_get_args ();
	
	if (func_num_args () == 0)
		die ( 'You must pass one rule at least to include_rules' );
	
	foreach ( $params as $key => $rule )
		require_once (__DIR__ . '/' . UQL_DIR_RULE . UQL_DIR_RULE_API . 'uql_rule_' . $rule . '.php');
}

function _f($entity_name) {
	$GLOBALS [sprintf ( UQL_FILTER_OBJECT_SYNTAX, $entity_name )] = new UQLFilter ( $entity_name );
	return $GLOBALS [sprintf ( UQL_FILTER_OBJECT_SYNTAX, $entity_name )];
}

function _r($entity_name) {
	$GLOBALS [sprintf ( UQL_RULE_OBJECT_SYNTAX, $entity_name )] = new UQLRule ( $entity_name );
	return $GLOBALS [sprintf ( UQL_RULE_OBJECT_SYNTAX, $entity_name )];
}

class underQL extends UQLBase {
	
	private $uql_database_handle;
	private $uql_entity_list; // load all tables' names from current database
	private $uql_loaded_entity_list;
	
	public function __construct($host = UQL_DB_HOST, $database_name = UQL_DB_NAME, $user = UQL_DB_USER, $password = UQL_DB_PASSWORD, $charset = UQL_DB_CHARSET) {
		
		/* check if we could use __invoke method syntax with underQL object or not */
		if (UQL_CONFIG_USE_INVOKE_CALL) {
			$php_ver = floatval ( PHP_VERSION );
			if ($php_ver < 5.3)
				$this->the_uql_error ( 'underQL work at least on PHP 5.3 to run invoke magic method. Go to UQL.php and change UQL_CONFIG_USE_INVOKE_CALL to false, after that, use loadEntity method rather thatn $_(\'table\') method' );
		}
		
		$this->uql_database_handle = new UQLConnection ( $host, $database_name, $user, $password, $charset );
		$this->the_uql_entity_list_init ();
		$this->uql_loaded_entity_list = array ();
	}
	
	public function the_uql_get_database() {
		return $this->uql_database_handle;
	}
	
	/* read all tables(entities) from current database and store them into array */
	protected function the_uql_entity_list_init() {
		
		$local_string_query = sprintf ( "SHOW TABLES FROM `%s`", $this->uql_database_handle->the_uql_get_database_name () );
		$local_query_result = mysql_query ( $local_string_query/*, $this->uql_database_handle -> getConnectionHandle()*/);
		if ($local_query_result) {
			$tables_count = mysql_num_rows ( $local_query_result );
			
			while ( $local_entity = mysql_fetch_row ( $local_query_result ) ) {
				$this->uql_entity_list [] = $local_entity [0];
			}
			
			@mysql_free_result ( $local_query_result );
		
		} else {
			$this->the_uql_error ( mysql_error(/*$this->uql_database_handle -> getConnectionHandle()*/) );
		}
	}
	
	/* create UQLEntity object and load all information about
       $entity_name table within it */
	public function the_uql_load_entity($entity_name) {
		
		if (strcmp ( $entity_name, '*' ) == 0) {
			$this->the_uql_load_all_entities ();
			return;
		}
		
		if (! in_array ( $entity_name, $this->uql_entity_list ))
			$this->the_uql_error ( $entity_name . ' is not a valid table name' );
		
		if (in_array ( $entity_name, $this->uql_loaded_entity_list ))
			return; // no action
		

		/* Create a global entity object. This part helps underQL to know
           the entity's object name for any loaded entity(table), therefore, underQL 
           could automatically use it in its own operations. */
		
		sprintf ( UQL_ENTITY_OBJECT_SYNTAX, $entity_name );
		$GLOBALS [sprintf ( UQL_ENTITY_OBJECT_SYNTAX, $entity_name )] = new UQLEntity ( $entity_name, $this->uql_database_handle );
	
	}
	
	/* You can load all tables as objects at once by use * symbol. This function
       used to do that. */
	public function the_uql_load_all_entities() {
		$entity_count = @count ( $this->uql_entity_list );
		for($i = 0; $i < $entity_count; $i ++)
			$this->the_uql_load_entity ( $this->uql_entity_list [$i] );
	}
	
	/* Helps underQL to use (object as function) syntax. However, this method used
       only with PHP 5.3.x and over */
	public function __invoke($entity_name) {
		$this->the_uql_load_entity ( $entity_name );
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
    of the ($_) 'under' object, but it is unpreferable(might be for future purposes).
 */
$_ = new underQL ();
?>