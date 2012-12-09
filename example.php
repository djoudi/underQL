<?php ini_set('display_errors',1);

require_once('underQL.php');

include_modules('template');

$_('users');

$temp =<<<TMP
<h1>Hi</h1>
<p>underQL is here</p><br />
TMP;

$p = $users->_('select');

$_->_('shutdown');


?>