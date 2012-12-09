<?php ini_set('display_errors',1);

require_once('underQL.php');

include_modules('json','sqlinjection');

$_('users');

//$template_module->setDelemiter('#','#');
//$users->name = "Abdullah'";
//$users->email = 'cs@code--.com';
$p = $users->_('select');

$_->_('shutdown');


?>