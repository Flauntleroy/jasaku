<?php
// Simple router for PHP built-in server to support CodeIgniter front controller

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $uri;

// Serve existing files (assets) directly
if ($uri !== '/' && file_exists($file) && is_file($file)) {
    return false;
}

// Fallback to front controller
require_once __DIR__ . '/index.php';