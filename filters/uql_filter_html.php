<?php

function ufilter_SQLi($name,$value,$in_out,$params = null)
{
    if($in_out == UQL_FILTER_IN)
        return mysql_real_escape_string($value);
    else
        return $value;
}

function ufilter_stripTags($name,$value,$in_out,$params = null)
{
    if($in_out == UQL_FILTER_IN)
    {
            return strip_tags($value);
    }
    else
        return $value;
}

?>