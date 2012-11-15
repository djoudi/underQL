<?php

function demo($v)
{
 return 'extra.'.$v.'.com';
}
_f('users')
        ->name('trim','in')
        ->name('callback','in','demo')
        ->name('sqli','in');
       
?>