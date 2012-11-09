<?php

include_rules('isemail');

$users_rule = new UQLRule('users');
$users_rule->email('isemail');

?>