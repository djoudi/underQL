<?php

/*
Table name : users
Fields : id - name - email - description
*/

require_once('underQL.php');

$_('users'); // $_('table_name') this will create a new object named $table_name

$users->email = 'cs.abdullah@hotmail.com';
$users->description = 'Programmer';

$result = $users->_('update'); // this will update ALL emails and descriptions to the above values

// $result will be TRUE or FALSE

$_->_('shutdown');

// or you can use it as :
//   $users->_('update',"put extra sql here like where");
//   $users->_('update',"WHERE $id = 10");
?>