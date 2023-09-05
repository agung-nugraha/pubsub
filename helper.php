<?php

require 'vendor/autoload.php';

use Symfony\Component\VarDumper\VarDumper;

if (!function_exists('dd')) {
    function dd($args) {
        VarDumper::dump($args);
        die;
    }
}

if (!function_exists('root_dir')) {
    function root_dir() {
        return __DIR__;
    }
}

if (!function_exists('get_dir')) {
    function get_dir(?string $path = null) {
        if ($path === null) {
            return root_dir();
        }

        return root_dir() . '/' . $path;
    }
}

