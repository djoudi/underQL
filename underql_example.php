<?php

require_once('underQL.php');
require_once(UQL_DIR_FILTER.'uql_filter_html.php');

$the_users_filter = new UQLFilter('users');
$the_users_filter->email('html',UQL_FILTER_OUT,'<h1>','</h1>');
$_->loadEntity('users');


$the_users->name = "underQL";
$the_users->email = "un@der.ql";
$the_users->save();

$result = $the_users->select('*');
echo $result->email;
?>