<?php ini_set('display_errors',1);

require_once('underQL.php');

include_modules('json','sqlinjection');

$_('users');

//$users->name = "Abdullah'";
//$users->email = 'cs@code--.com';
$users->_('select');

echo '<pre>';
var_dump($json_module->toObject());
echo '</pre>';
$_->_('shutdown');
?>