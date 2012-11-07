<?php

//mysql_error_messages and error messages in general
//enable disable filter engine rule engine

require_once('underQL.php');

$_('users');

$users->id = 700;
$users->name = "Saad";
$users->email = "saad@saudi.com";
$users->description = "Programmer";

$users->save();


$result = $users->select('*');

echo $result->email;

?>