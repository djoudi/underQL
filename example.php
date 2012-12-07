<?php ini_set('display_errors',1);

require_once('underQL.php');
include_modules('demo');

$_('users');

//$users->name = 'Abdullah';
//$users->email = 'cs.abdullah@hotmail.com';
//$users->description = 'www.abdullaheid.net';

$users->_('select');

$_->_('shutdown');
?>