<?php

$baseUrl = getenv('APP_BASE_URL');

if ($baseUrl === false || trim($baseUrl) === '') {
    $projectRoot = realpath(__DIR__ . '/..');
    $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath((string) $_SERVER['DOCUMENT_ROOT']) : false;

    if ($projectRoot !== false && $documentRoot !== false) {
        $projectRoot = str_replace('\\', '/', $projectRoot);
        $documentRoot = rtrim(str_replace('\\', '/', $documentRoot), '/');

        if (str_starts_with($projectRoot, $documentRoot)) {
            $relativePath = substr($projectRoot, strlen($documentRoot));
            $baseUrl = $relativePath !== false ? $relativePath : '';
        }
    }

    if (!is_string($baseUrl) || trim($baseUrl) === '') {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $scriptDir = $scriptDir === '.' ? '' : rtrim($scriptDir, '/');
        $baseUrl = preg_replace('#/public$#', '', $scriptDir) ?: '';
    }
}

$baseUrl = '/' . ltrim((string) $baseUrl, '/');
$baseUrl = $baseUrl === '/' ? '' : rtrim($baseUrl, '/');

return [
    'name' => getenv('APP_NAME') ?: 'Capela Market',
    'base_url' => $baseUrl,
    'uploads_url' => ($baseUrl !== '' ? $baseUrl : '') . '/uploads',
    'timezone' => getenv('APP_TIMEZONE') ?: 'America/Fortaleza',
    'currency' => getenv('APP_CURRENCY') ?: 'BRL',
    'cache_path' => __DIR__ . '/../storage/cache',
];
