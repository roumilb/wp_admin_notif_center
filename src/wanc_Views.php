<?php


namespace WANC;


class wanc_Views
{
    public static function includeViews($view, $data = [])
    {
        $dataView = $data;
        $viewFile = __DIR__.'/views/'.$view.'.php';
        if (file_exists($viewFile)) {
            include __DIR__.'/views/'.$view.'.php';
        }
    }
}
