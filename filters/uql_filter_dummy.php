<?php

function ufilter_dummy($name,$value,$in_out,$params = null)
{
    if($in_out == UQL_FILTER_IN | UQL_FILTER_OUT)
        return $value;
    else if($in_out == UQL_FILTER_IN)
        return $value;
    else if($in_out == UQL_FILTER_OUT)
        return $value;
}

?>