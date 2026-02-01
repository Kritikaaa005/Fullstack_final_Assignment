<?php
//gets only path from url..no query params
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Base path adjustment similar to Router
$scriptUrl = $_SERVER['SCRIPT_NAME'];
$basePath = str_replace('/index.php', '', $scriptUrl);
if ($basePath !== '' && strpos($currentPath, $basePath) === 0) {
    $currentPath = substr($currentPath, strlen($basePath));
}

if (!isset($_SESSION['user_id']) && $currentPath !== '/login') {
    header("Location: " . url('/login'));
    exit;
}
