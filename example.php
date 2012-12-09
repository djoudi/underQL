<?php ini_set('display_errors',1);

require_once('underQL.php');

include_modules('template');

$_('users');

$temp =<<<TMP

<div>
<strong>id</strong> : #id# <br />
<strong>Name</strong> : #name# <br />
<sup>Email</sup> : #email# <br />
<sub>Description</sub> : #description#
</div>

TMP;

$template_module->setTemplateFromString($temp);

$p = $users->_('select');

echo $template_module->getResult();

$_->_('shutdown');


?>