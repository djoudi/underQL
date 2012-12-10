<?php

/*
Table name : users
Fields : id - name - email - description
*/

require_once('underQL.php');

$the_array['id'] = 10;
$the_array['name'] = 'Abdullah';
$the_array['email'] = 'cs.abdullah@hotmail.com';
$the_array['description'] = 'Programmer';

$_('users'); // $_('table_name') this will create a new object named $table_name

$result = $users->_('insert_from_array',$the_array); // execute insert command

// $result will be TRUE or FALSE

$_->_('shutdown');

?>