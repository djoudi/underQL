<?php

require_once('underQL.php');

$_->loadEntity('users');


$a['name'] = 'Salem Ba-Hamden';
$a['email'] = 'salem@gmail.com';

$users->saveFromArray($a);
//$users->updateFromArray($array);


?>