<?php

/*
Table name : users
Fields : id - name - email - description
*/

/*
 * update_where_n : replace [n] with any field that exists in your table. For example,
 *  we have 4 fields here : id - name - email - description, that is, you can call it like:
 *
 *  1- update_where_id($id)
 *  2- update_where_name($name)
 *  3- update_where_email($email)
 *  4- update_where_description($description)
 * 
 */
require_once('underQL.php');

$_('users'); // $_('table_name') this will create a new object named $table_name

$users->email = 'cs.abdullah@hotmail.com';
$users->description = 'Programmer';

$result = $users->_('update_where_name','Abdullah');

// $result will be TRUE or FALSE

$_->_('shutdown');

?>