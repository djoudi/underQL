<?php

/*
Table name : users
Fields : id - name - email - description
*/

require_once('underQL.php');

$_('users'); // $_('table_name') this will create a new object named $table_name

$users->email = 'cs.abdullah@hotmail.com';
$users->description = 'Programmer';

$users->_('update'); // this will update ALL emails and descriptions to the above values

// or you can yous it as :
//   $users->_('update',"put extra sql here like where");
//   $users->_('update',"WHERE $id = 10");
?>