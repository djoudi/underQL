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

function include_filters() {
	$params = func_get_args ();
	
	if (func_num_args () == 0)
		die ( 'You must pass one filter at least to include_filters' );
	
	foreach ( $params as $key => $filter )
		require_once (__DIR__ . '/' . UQL_DIR_FILTER . UQL_DIR_FILTER_API . 'ufilter_' . $filter . '.php');
}

function include_rules() {
	$params = func_get_args ();
	
	if (func_num_args () == 0)
		die ( 'You must pass one rule at least to include_rules' );
	
	foreach ( $params as $key => $rule )
		require_once (__DIR__ . '/' . UQL_DIR_RULE . UQL_DIR_RULE_API . 'urule_' . $rule . '.php');
}

function _f($entity_name) {
	$GLOBALS [sprintf ( UQL_FILTER_OBJECT_SYNTAX, $entity_name )] = new UQLFilter ( $entity_name );
	return $GLOBALS [sprintf ( UQL_FILTER_OBJECT_SYNTAX, $entity_name )];
}

function _r($entity_name) {
	$GLOBALS [sprintf ( UQL_RULE_OBJECT_SYNTAX, $entity_name )] = new UQLRule ( $entity_name );
	return $GLOBALS [sprintf ( UQL_RULE_OBJECT_SYNTAX, $entity_name )];
}
?>