<?php


namespace WANC;


class Debug
{
    public static function dump()
    {
        echo '<pre style="margin-left: 300px">';
        var_dump(func_get_args());
        echo '</pre>';
    }
}
