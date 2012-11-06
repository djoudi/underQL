<?php

require_once('underQL.php');

$_->loadEntity('users');


$users->name = "underQL";
$users->email = "un@der.ql";
$users->save();

//$result = $the_users->select('*');
$result->plugin('toXML');


?>