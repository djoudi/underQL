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

class UQLFilterEngine extends UQLBase {
	
	private $uql_filter_object;
	private $uql_values_map;
	//current inserted | updated $key => $value pairs
	private $uql_in_out_flag;
	// specify if the engine for input or output
	

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
?>