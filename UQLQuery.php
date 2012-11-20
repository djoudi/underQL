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
?>