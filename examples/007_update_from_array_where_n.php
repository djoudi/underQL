<?php

/*
Table name : users
Fields : id - name - email - description
*/

/*
 * update_from_array_where_n : replace [n] with any field that exists in your table. For example,
 *  we have 4 fields here : id - name - email - description, that is, you can call it like:
 *
 *  1- update_from_array_where_id($the_array,$id)
 *  2- update_from_array_where_where_name($the_array,$name)
 *  3- update_from_array_where_email($the_array,$email)
 *  4- update_from_array_where_description($the_array,$description)
 * 
 */
require_once('underQL.php');

$_('users'); // $_('table_name') this will create a new object named $table_name

$the_array['email'] = 'cs.abdullah@hotmail.com';
$the_array['description'] = 'Programmer';

$result = $users->_('update_from_array_where_name',$the_array,'Abdullah');

// $result will be TRUE or FALSE

?>