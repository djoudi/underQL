<?php


require_once('filters_api/uql_filter_html.php');
require_once('filters_api/uql_filter_xss.php');

$users_filter = new UQLFilter('users');

$users_filter->name('xss',UQL_FILTER_IN);
$users_filter->name('html',UQL_FILTER_OUT);
$users_filter->email('xss',UQL_FILTER_IN);
$users_filter->description('html',UQL_FILTER_OUT);


?>