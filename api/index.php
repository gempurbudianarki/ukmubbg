<?php

// Ensure /tmp writable directories exist on Vercel serverless environment
$dirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/framework/views',
    '/tmp/framework/cache',
    '/tmp/framework/sessions',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Forward request to Laravel public front controller
require __DIR__ . '/../public/index.php';
