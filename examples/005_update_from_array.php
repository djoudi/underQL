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

// this will update ALL emails and descriptions to the above values
$result = $users->_('update_from_array',$the_array);

// $result will be TRUE or FALSE

$_->_('shutdown');

//  You can use it as :
//   $users->_('update_from_array',$the_array,"put extra sql here like where");
//   $users->_('update_from_array',$the_array,"WHERE $id = 10");
// NOTE : it is exactly works like $users->_('update'), but it is takes its values
//         from array.

?>