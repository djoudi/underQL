<?php

function demo($v)
{
 return 'extra_'.$v.'_com';
}
_f('users')
        ->name('callback','in','demo')
        ->name('trim','in')
        ->name('sqli','in');
        
?>