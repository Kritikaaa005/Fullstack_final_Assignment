<?php

spl_autoload_register(function ($class) {
    $dirs = [
        __DIR__ . '/../controllers/',
        __DIR__ . '/../models/',
        __DIR__ . '/../core/',
    ];

    foreach ($dirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
