<?php

include_filters('md5','removetags');

$users_filter = new UQLFilter('users');
$users_filter->name('md5',UQL_FILTER_IN);
$users_filter->description('removetags',UQL_FILTER_IN);
//$users_filter->jk('md5',UQL_FILTER_IN);




?>