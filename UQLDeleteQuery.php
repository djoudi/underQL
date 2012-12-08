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

class UQLDeleteQuery extends UQLBase {
	
	private $um_query;
	private $um_abstract_entity;
	
	public function __construct(&$database_handle, &$abstract_entity) {
		if ((! $database_handle instanceof UQLConnection) || (! $abstract_entity instanceof UQLAbstractEntity))
			UQLBase::underql_error ( 'Invalid database handle' );
		
		$this->um_query = new UQLQuery ( $database_handle );
		$this->um_abstract_entity = $abstract_entity;
	}
	
	protected function underql_format_delete_query($extra = null) {
		
		$delete_query = 'DELETE FROM `' . $this->um_abstract_entity->underql_get_entity_name () . '`';
		if ($extra != null)
			$delete_query .= ' ' . $extra;
		
		return $delete_query;
	}
	
	public function underql_delete($extra = '') {
		$query = $this->underql_format_delete_query ( $extra );
		return $this->um_query->underql_execute_query ( $query );
	}

	public function underql_delete_where_n($field_name,$value)
	{
	  $field_object = $this->um_abstract_entity->underql_get_field_object($field_name);
	  if($field_object != null)
	  {
	    if($field_object->numeric)
	     return $this->underql_delete("WHERE `$field_name` = $value");
	    else
	     return $this->underql_delete("WHERE `$field_name` = '$value'"); 
	  }
	  
	  return false;
	}
	
	public function __destruct() {
		$this->um_query = null;
		$this->um_abstract_entity = null;
	}

}

?>