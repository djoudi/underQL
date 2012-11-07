<?php

require_once('underQL.php');

$_('users');

$users->id = 10;
$users->name = "Jabali";
$users->email = "m@jabali.com";
//$users->xyz = 10;
$users->save();

?>