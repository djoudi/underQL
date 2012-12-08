<?php ini_set('display_errors',1);

require_once('underQL.php');

include_modules('sqlinjection');

$_('users');

$users->name = "Abdullah'";
$users->_('insert');

$_->_('shutdown');
?>