<?php

require_once('underQL.php');
require_once(UQL_DIR_FILTER.'uql_filter_html.php');

$_->loadEntity('users');

$result = $the_users->select('*');

echo $result->email;
?>