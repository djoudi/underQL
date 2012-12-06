<?php 


require_once('underQL.php');

_f('users')->name('required')->_('add_alias','name','إسم التلميذ');

$_('users');

//$users->id = 10;
$users->name = "Abdullah";
$users->description = "www.abdullaheid.net";

$users->_('insert');


$v = array('id' => 2, 'name' => 'Abdullah');

$_('@close db');
?>