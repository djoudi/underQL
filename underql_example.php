<?php

require_once('underQL.php');

$_->loadEntity('users');


$the_users->name = "underQL";
$the_users->email = "un@der.ql";
$the_users->save();

//$result = $the_users->select('*');


?>