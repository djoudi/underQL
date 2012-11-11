<?php

include_filters('md5','removetags','phpcolor');

$users_filter = new UQLFilter('users');
//$users_filter->name('md5',UQL_FILTER_IN|UQL_FILTER_IN);
$users_filter->name('md5','in');
$users_filter->description('phpcolor','out');


?>