<?php

$publicPath = realpath(__DIR__.'/../public');
$requestPath = trim((string) ($_GET['path'] ?? ''), '/');
$requestPath = rawurldecode($requestPath);
$filePath = realpath($publicPath.'/'.$requestPath);

if (
    $publicPath === false ||
    $filePath === false ||
    ! str_starts_with($filePath, $publicPath.DIRECTORY_SEPARATOR) ||
    ! is_file($filePath)
) {
    http_response_code(404);
    echo 'Not found';

    return;
}

$extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
$contentTypes = [
    'css' => 'text/css; charset=utf-8',
    'js' => 'application/javascript; charset=utf-8',
    'json' => 'application/json; charset=utf-8',
    'ico' => 'image/x-icon',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'svg' => 'image/svg+xml',
    'webp' => 'image/webp',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
];

header('content-type: '.($contentTypes[$extension] ?? 'application/octet-stream'));
header('cache-control: public, max-age=31536000, immutable');
header('content-length: '.filesize($filePath));

readfile($filePath);
