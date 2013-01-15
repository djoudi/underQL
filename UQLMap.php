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

    private $um_map_list;
    private $um_elements_count;

    public function __construct() {
        $this->um_map_list = array ();
        $this->um_elements_count = 0;
    }

    public function underql_add_element($key, $value) {

        if ($this->underql_find_element ( $key ) == null)
            $this->um_elements_count ++;

        $this->um_map_list [$key] = $value;
    }

    public function underql_find_element($key) {
        if ($this->underql_is_element_exist ( $key ))
            return $this->um_map_list [$key];

        return null;
    }

    public function underql_is_element_exist($key) {
        if ($this->um_elements_count <= 0)
            return false;

        if (@array_key_exists ( $key, $this->um_map_list ))
            return true;

        return false;
    }

    public function underql_get_count() {
        return count ( $this->um_map_list );
    }

    public function underql_remove_element($key) {

        if ($this->underql_is_element_exist ( $key )) {
            unset ( $this->um_map_list [$key] );
            $this->um_elements_count --;
        }
    }

    public function underql_is_empty() {
        return $this->um_elements_count == 0;
    }

    public function underql_map_callback($callback) {
        if (! $this->underql_is_empty ())
            return array_map ( $callback, $this->map_list );
    }

    public function underql_get_map() {
        return $this->um_map_list;
    }

    public function underql_set_map($the_map) {
        if(is_array($the_map))
            $this->um_map_list = $the_map;
    }

    public function __destruct() {

        $this->um_map_list = null;
        $this->um_elements_count = 0;
    }

}
?>