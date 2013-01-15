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

    private $um_entity_name;
    private $um_fields;
    private $um_fields_count;

    public function __construct($entity_name, &$database_handle) {

        $this->um_entity_name = null;
        $this->um_fields = null;
        $this->um_fields_count = 0;

        $this->underql_set_entity_name ( $entity_name, $database_handle );
    }

    public function underql_set_entity_name($entity_name, &$database_handle) {

        if (($database_handle instanceof UQLConnection)) {
            $this->um_entity_name = $entity_name;
            $string_query = sprintf ( "SHOW COLUMNS FROM `%s`", $this->um_entity_name );
            $query_result = mysql_query ( $string_query );
            if ($query_result) {
                $this->um_fields_count = mysql_num_rows ( $query_result );
                @mysql_free_result ( $query_result );

                $fields_list = mysql_list_fields ( $database_handle->underql_get_database_name (), $this->um_entity_name );
                $this->um_fields = array ();

                $i = 0;
                while ( $i < $this->um_fields_count ) {
                    $field = mysql_fetch_field ( $fields_list );
                    $this->um_fields [$field->name] = $field;
                    $i++;
                }

                @mysql_free_result ( $fields_list );
            } else {
                UQLBase::underql_error( mysql_error() );
            }
        }
    }

    public function underql_get_entity_name() {
        return $this->um_entity_name;
    }

    public function underql_is_field_exist($name) {
        return (($this->um_fields != null) && (array_key_exists ( $name, $this->um_fields )));
    }

    public function underql_get_field_object($name) {
        if ($this->underql_is_field_exist ( $name ))
            return $this->um_fields [$name];
        return null;
    }

    public function underql_get_all_fields() {
        return $this->um_fields;
    }

    public function underql_get_fields_count() {
        return $this->um_fields_count;
    }

    public function __destruct() {
        $this->um_entity_name = null;
        $this->um_fields = null;
        $this->um_fields_count = 0;
    }

}
?>