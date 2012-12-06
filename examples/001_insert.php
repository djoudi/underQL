<?php

/*
Table name : users
Fields : id - name - email - description
*/

require_once('underQL.php');

$_('users');

$users->id = 10;
$users->name = 'Abdullah';
$users->email = 'cs.abdullah@hotmail.com';
$users->description = 'Programmer';

$users->_('insert');

?>