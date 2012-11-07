<?php

//mysql_error_messages and error messages in general
//enable disable filter engine rule engine

require_once('underQL.php');
function urule_length($name,$value,$alias,$params = null)
{
 
}
$_('users');

$users->id = 700;
$users->name = "Saad";
$users->email = "saad@saudi.com";
$users->description = "Programmer";

$users->save();


$result = $users->select('*');

echo $result->email;

?>