<?php

require_once('underQL.php');

$_('*');

$r = $users->select('*');

echo $r->name;

?>