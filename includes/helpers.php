<?php

function url($path = '')
{
    $scriptUrl = $_SERVER['SCRIPT_NAME'];
    $basePath = str_replace('/index.php', '', $scriptUrl);

    // Ensure path starts with /
    if ($path !== '' && $path[0] !== '/') {
        $path = '/' . $path;
    }

    return $basePath . $path;
}
