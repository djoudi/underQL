<?php ini_set('display_errors',1);

require_once('underQL.php');


_f('users')->email('md5');
$_('users');

$users->email = 'cs.abdullah@hotmail.com';
$users->_('insert');

$_->_('shutdown');


?>