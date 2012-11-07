<?php

require_once('underQL.php');

$_('users');


$a['name'] = 'Jameel Ba-Hamden';
$a['email'] = 'seven@gmail.com';

$users->saveFromArray($a);
//$users->modifyFromArray($array);


?>