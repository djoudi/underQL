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
	
	public function the_uql_map_callback($callback) {
		if (! $this->isEmpty ())
			return array_map ( $callback, $this->map_list );
	}
	
	public function the_uql_get_map() {
		return $this->uql_map_list;
	}
	
	public function the_uql_set_map($the_map)
	{
	  if(is_array($the_map)
	   $this->uql_map_list = $the_map;
	}
	
	public function __destruct() {
		
		$this->uql_map_list = null;
		$this->uql_elements_count = 0;
	}

}
?>