<?php

/*
Table name : users
Fields : id - name - email - description
*/

require_once('underQL.php');

$_('users'); // $_('table_name') this will create a new object named $table_name

$users->id = 10; // ignore this if it is primary and auto_increment
$users->name = 'Abdullah';
$users->email = 'cs.abdullah@hotmail.com';
$users->description = 'Programmer';

$users->_('insert'); // execute insert command

?>