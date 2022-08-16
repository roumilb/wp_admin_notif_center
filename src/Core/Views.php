<?php


namespace WANC\Core;


class Views
{
    public static function includeViews($view, $data = [])
    {
        $dataView = $data;
        $viewFile = __DIR__ . '/../Views/' .$view.'.php';
        if (file_exists($viewFile)) {
            include __DIR__ . '/../Views/' .$view.'.php';
        }
    }
}
