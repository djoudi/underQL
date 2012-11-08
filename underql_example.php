<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL);
require_once('underQL.php');
require_once('filters/uql_filter_html.php');

$_('users');

$users_filter = new UQLFilter('users');
$users_filter->name('SQLi',UQL_FILTER_IN);
$users_filter->name('stripTags',UQL_FILTER_IN);


//$users->id = 772;
$users->name = "<h1>abdullaheid</h2>";

$r = $users->insert();
//echo '<pre>';
//var_dump($r);
//echo '</pre>';
//echo mysql_error();
?>