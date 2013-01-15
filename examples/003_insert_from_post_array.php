<?php

/*
Table name : users
Fields : id - name - email - description
*/

require_once('underQL.php');

$_('users'); // $_('table_name') this will create a new object named $table_name

// underQL will try to find id - name - email - description keys and ignore the others.
$result = $users->_('insert_from_array',$_POST); // you can use $_GET or $_REQUEST if you would or any other array.

// $result will be TRUE or FALSE

$_->_('shutdown');

?>