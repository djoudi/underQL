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

class UQLRule extends UQLBase {

    private $um_entity_name;
    private $um_alises_map;
    private $um_rules_map;

    public function __construct($entity_name) {

        $this->um_entity_name = $entity_name;
        $this->um_alises_map = new UQLMap ();
        $this->um_rules_map = new UQLMap ();
    }

    public function __call($function_name, $parameters) {

        $local_params_count = count ( $parameters );
        if ($local_params_count == 0)
            return;

        $this->underql_add_rule ( $function_name, $parameters );
        return $this;
    }

    protected function underql_add_rule($field, $rule) {

        if (! $this->um_rules_map->underql_is_element_exist ( $field ))
            $this->um_rules_map->underql_add_element ( $field, new UQLMap () );

        $local_rule = $this->um_rules_map->underql_find_element ( $field );
        $local_rule->underql_add_element ( $local_rule->underql_get_count (), array ('rule' => $rule, 'is_active' => true ) );

        $this->um_rules_map->underql_add_element ( $field, $local_rule );
    }

    protected function underql_set_rule_activation($field_name, $rule_name, $activation) {
        $local_rule = $this->um_rules_map->underql_find_element ( $field_name );

        if (! $local_rule)
            UQLBase::underql_error ( 'You can not stop a rule for unknown field (' . $field_name . ')' );

        for($i = 0; $i < $local_rule->underql_get_count (); $i ++) {
            $target_rule = $local_rule->underql_find_element ( $i );
            if (strcmp ( $target_rule ['rule'] [0], $rule_name ) == 0) {
                $target_rule ['is_active'] = $activation;
                $local_rule->underql_add_element ( $i, array ('rule' => $target_rule ['rule'], 'is_active' => $activation ) );
                $this->um_rules_map->underql_add_element ( $field_name, $local_rule );
            }
        }

    }

    public function underql_start_rules(/*$field_name,$rule_name*/) {
        $params_count = func_num_args ();
        if ($params_count < 2)
            UQLBase::underql_error ( 'start_rules accepts two parameters at least' );

        $rules_counts = $params_count - 1;
        // remove field name
        $parameters = func_get_args ();
        if ($rules_counts == 1) {
            $this->underql_set_rule_activation ( $parameters [0], $parameters [1], true );
            return;
        } else {
            for($i = 0; $i < $rules_counts - 1; $i ++)
                $this->underql_set_rule_activation ( $parameters [0], $parameters [$i + 1], true );
        }
    }

    public function underql_stop_rules(/*$field_name,$rule_name*/) {
        $params_count = func_num_args ();
        if ($params_count < 2)
            UQLBase::underql_error ( 'stop_rules accepts two parameters at least' );

        $rules_counts = $params_count - 1;
        $parameters = func_get_args ();
        if ($rules_counts == 1) {
            $this->underql_set_rule_activation ( $parameters [0], $parameters [1], false );
            return;
        } else {
            for($i = 0; $i < $rules_counts - 1; $i ++)
                $this->underql_set_rule_activation ( $parameters [0], $parameters [$i + 1], false );
        }
    }

    public function underql_get_rules_by_field_name($field_name) {

        return $this->um_rules_map->underql_find_element ( $field_name );
    }

    public function underql_add_alias($key, $value) {

        $this->um_alises_map->underql_add_element ( $key, $value );
    }

    public function underql_get_alias($key) {

        return $this->um_alises_map->underql_find_element ( $key );
    }

    public function underql_get_rules() {
        return $this->um_alises_map;
    }

    public function underql_get_entity_name() {
        return $this->um_entity_name;
    }

    public function underql_get_aliases() {
        return $this->um_alises_map;
    }

    public static function underql_find_rule_object($entity) {

        $rule_object_name = sprintf ( UQL_RULE_OBJECT_SYNTAX, $entity );

        if (isset ( $GLOBALS [$rule_object_name] ))
            $rule_object = $GLOBALS [$rule_object_name];
        else
            $rule_object = null;

        return $rule_object;

    }

    public function __destruct() {

        $this->um_entity_name = null;
        $this->um_rules_map = null;
        $this->um_alises_map = null;
    }

}
?>