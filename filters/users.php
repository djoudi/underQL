<?php

function include_filter($filter_name)
{
 return require_once(UQL_DIR_FILTER.UQL_DIR_FILTER_API.$filter_name);
}

//require_once('filters_api/uql_filter_md5.php');
//require_once('filters_api/uql_filter_removetags.php');

x('filters_api/uql_filter_md5.php');
x('filters_api/uql_filter_removetags.php');
//require_once(_f('md5'));

$users_filter = new UQLFilter('users');
$users_filter->name('md5',UQL_FILTER_IN);
//$users_filter->description('removetags',UQL_FILTER_IN);




?>