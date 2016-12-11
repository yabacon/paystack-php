<?php

/**
 * Paystack Autoloader
 * For use when library is being used without composer
 */
spl_autoload_register(
    function ($class_name) {
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $file .= str_replace([ 'Yabacon\\', '\\' ], ['', DIRECTORY_SEPARATOR ], $class_name) . '.php';
        include_once $file;
    }
);
