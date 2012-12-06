<?php 


require_once('underQL.php');

$_('users');

//$users->id = 10;
$users->name = "Abdullah";
$users->description = "www.abdullaheid.net";

$users->_('insert');


$v = array('id' => 2, 'name' => 'Abdullah');

$_('@close db');
?>