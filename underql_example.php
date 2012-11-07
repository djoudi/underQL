<?php

require_once('underQL.php');

$_('users');


$a['name'] = 'Zeba Ba-Hamden';
$a['email'] = 'Meba@gmail.com';

echo '<pre>';
//var_dump($users);
echo '</pre>';
$users->modifyFromArrayWhereID($a,5028);


?>