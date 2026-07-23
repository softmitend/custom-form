<?php

use App\Models\WebsiteProjectRequest;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/vercel_bootstrap.php';

header('content-type: application/json');

$autoload = __DIR__.'/../vendor/autoload.php';
$lock = __DIR__.'/../composer.lock';
$packages = is_file($lock) ? json_decode((string) file_get_contents($lock), true) : [];
$symfonyVersion = null;

foreach (($packages['packages'] ?? []) as $package) {
    if (($package['name'] ?? null) === 'symfony/http-foundation') {
        $symfonyVersion = $package['version'] ?? null;
        break;
    }
}

$result = [
    'ok' => true,
    'php_version' => PHP_VERSION,
    'sapi' => PHP_SAPI,
    'autoload_exists' => is_file($autoload),
    'symfony_http_foundation' => $symfonyVersion,
    'app_key_present' => (bool) getenv('APP_KEY'),
    'app_env' => getenv('APP_ENV') ?: null,
    'app_debug' => getenv('APP_DEBUG') ?: null,
    'db_connection' => getenv('DB_CONNECTION') ?: null,
    'database_url_present' => (bool) (getenv('DATABASE_URL') ?: getenv('DB_URL')),
    'laravel_boot' => false,
    'encrypter_ok' => false,
    'db_ok' => false,
    'form_requests_query_ok' => false,
    'vite_manifest_exists' => is_file(__DIR__.'/../public/build/manifest.json'),
    'home_render_ok' => false,
    'home_render_status' => null,
    'form_render_ok' => false,
    'form_render_status' => null,
];

try {
    if (is_file($autoload)) {
        require_once $autoload;
        $app = require __DIR__.'/../bootstrap/app.php';

        $kernel = $app->make(Kernel::class);
        $kernel->bootstrap();

        $result['laravel_boot'] = true;
        $result['app_key_present'] = (bool) config('app.key');
        $result['app_env'] = config('app.env');
        $result['app_debug'] = config('app.debug');
        $result['db_connection'] = config('database.default');
        $result['database_url_present'] = (bool) (env('DATABASE_URL') ?: env('DB_URL'));

        try {
            $app->make('encrypter');
            $result['encrypter_ok'] = true;
        } catch (Throwable $exception) {
            $result['encrypter_error'] = $exception::class.': '.$exception->getMessage();
        }

        try {
            $app->make('db')->connection()->select('select 1 as ok');
            $result['db_ok'] = true;
        } catch (Throwable $exception) {
            $result['db_error'] = $exception::class.': '.$exception->getMessage();
        }

        try {
            WebsiteProjectRequest::query()->count();
            $result['form_requests_query_ok'] = true;
        } catch (Throwable $exception) {
            $result['form_requests_query_error'] = $exception::class.': '.$exception->getMessage();
        }

        foreach (['home' => '/', 'form' => '/formulir'] as $key => $path) {
            try {
                $request = Request::create($path, 'GET');
                $response = $app->handle($request);
                $result[$key.'_render_status'] = $response->getStatusCode();
                $result[$key.'_render_ok'] = $response->getStatusCode() < 500;
            } catch (Throwable $exception) {
                $result[$key.'_render_error'] = $exception::class.': '.$exception->getMessage();
            }
        }
    }
} catch (Throwable $exception) {
    $result['laravel_error'] = $exception::class.': '.$exception->getMessage();
}

echo json_encode($result, JSON_PRETTY_PRINT);
