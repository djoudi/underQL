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
?>