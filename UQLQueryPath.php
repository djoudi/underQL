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

class UQLQueryPath extends UQLBase {

    public $um_abstract_entity;
    public $um_query_object;
    public $um_filter_engine;

    public function __construct(&$database_handle, &$abstract_entity) {

        if ($abstract_entity instanceof UQLAbstractEntity)
            $this->um_abstract_entity = $abstract_entity;
        else
            UQLBase::underql_error ( 'You must provide a appropriate value for abstract_entity parameter' );

        $this->um_query_object = new UQLQuery ( $database_handle );
        $filter_object = UQLFilter::underql_find_filter_object ( $this->um_abstract_entity->underql_get_entity_name () );
        $this->um_filter_engine = new UQLFilterEngine ( $filter_object, UQL_FILTER_OUT );
    }

    public function underql_execute_query($query) {

        if ($this->um_query_object->underql_execute_query ( $query )) {
            UQLModuleEngine::underql_module_run_output($this);
            // $this->underql_reset();
            return true;
        }

        return false;
    }

    public function underql_fetch() {
        return $this->um_query_object->underql_fetch_row ();
    }

    public function underql_reset() {
        return $this->um_query_object->underql_reset_result();
    }

    public function underql_count() {
        return $this->um_query_object->underql_get_count ();
    }

    public function underql_query_object() {
        return $this->um_query_object;
    }

    public function underql_abstract_entity() {
        return $this->um_abstract_entity;
    }

    public function underql_fields() {
        return $this->um_query_object->underql_get_current_query_fields ();
    }

    public function underql_field_info($field_name) {
        return $this->um_abstract_entity->underql_get_field_object($field_name);
    }

    public function underql_entity_name() {
        return $this->um_abstract_entity->underql_get_entity_name();
    }

    public function __get($key) {

        if (! $this->um_abstract_entity->underql_is_field_exist ( $key ))
            UQLBase::underql_error ( "[$key] does not exist in ".$this->um_abstract_entity->underql_get_entity_name());

        $current_query_fields = $this->underql_fields();//$this->um_query_object->underql_get_current_query_fields ();
        if ($current_query_fields == null)
            UQLBase::underql_error ( "[$key] does not exist in the current query fields" );

        foreach ( $current_query_fields as $field_name ) {
            if (strcmp ( $key, $field_name ) == 0) {
                $current_row = $this->um_query_object->underql_get_current_row ();
                if ($current_row == null)
                    return null;
                else {
                    return $this->um_filter_engine->underql_apply_filter ( $key, $current_row->$key );
                }
            }
        }

        return null;
    }

    public function __destruct() {

        $this->um_abstract_entity = null;
        $this->um_query_object = null;

        //$this->plugin = null;
    }

}
?>