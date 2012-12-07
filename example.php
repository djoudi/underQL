<?php ini_set('display_errors',1);

require_once('underQL.php');
include_modules('demo');

$_('users');

//$users->name = 'Abdullah';
//$users->email = 'cs.abdullah@hotmail.com';
//$users->description = 'www.abdullaheid.net';

//$demo_module->stopModule();
//$demo_module->restartModule();
$p = $users->_('select');

while($p->_('get_next'))
    {
      echo $p->id.' - '.$p->name;
      echo '<br />';
    }
$_->_('shutdown');
?>