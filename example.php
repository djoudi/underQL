<?php ini_set('display_errors',1);

require_once('underQL.php');
include_modules('json');

$_('users');

$users->_('select');

echo '<pre>';
var_dump($json_module->getSource());
echo '</pre>';

$_->_('shutdown');
?>