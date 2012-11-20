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
?>