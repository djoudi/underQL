<?php ini_set('display_errors',1);

require_once('underQL.php');
include_modules('json');

$_('users');

$a ['name'] = 'Fahad';

$users->_('delete_where_name','Fahad');


echo '<pre>';
var_dump($json_module->getSource());
echo '</pre>';

$_->_('shutdown');
?>