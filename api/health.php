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
    'vite_assets_readable' => false,
    'vite_css_files' => [],
    'vite_js_files' => [],
    'vite_css_route_ok' => false,
    'vite_css_route_status' => null,
    'vite_css_route_content_type' => null,
    'asset_url' => getenv('ASSET_URL') ?: null,
    'home_render_ok' => false,
    'home_render_status' => null,
    'home_css_links' => [],
    'home_contains_vite_css' => false,
    'form_render_ok' => false,
    'form_render_status' => null,
    'form_css_links' => [],
    'form_contains_vite_css' => false,
];

if ($result['vite_manifest_exists']) {
    $manifest = json_decode((string) file_get_contents(__DIR__.'/../public/build/manifest.json'), true);
    $assetFiles = [];
    $cssFiles = [];
    $jsFiles = [];

    foreach (($manifest ?? []) as $entry) {
        if (isset($entry['file'])) {
            $assetFiles[] = $entry['file'];

            if (str_ends_with($entry['file'], '.js')) {
                $jsFiles[] = '/build/'.$entry['file'];
            }

            if (str_ends_with($entry['file'], '.css')) {
                $cssFiles[] = '/build/'.$entry['file'];
            }
        }

        foreach (($entry['css'] ?? []) as $cssFile) {
            $assetFiles[] = $cssFile;
            $cssFiles[] = '/build/'.$cssFile;
        }

        foreach (($entry['assets'] ?? []) as $assetFile) {
            $assetFiles[] = $assetFile;
        }
    }

    $missingAssets = array_values(array_filter(array_unique($assetFiles), function (string $assetFile): bool {
        return ! is_file(__DIR__.'/../public/build/'.$assetFile);
    }));

    $result['vite_assets_readable'] = count($missingAssets) === 0;
    $result['vite_missing_assets'] = $missingAssets;
    $result['vite_css_files'] = array_values(array_unique($cssFiles));
    $result['vite_js_files'] = array_values(array_unique($jsFiles));
}

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
        $result['asset_url'] = config('app.asset_url');
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
                $content = (string) $response->getContent();
                $result[$key.'_render_status'] = $response->getStatusCode();
                $result[$key.'_render_ok'] = $response->getStatusCode() < 500;
                preg_match_all('/<link[^>]+href=["\']([^"\']+\.css[^"\']*)["\']/i', $content, $matches);
                $result[$key.'_css_links'] = $matches[1] ?? [];
                $result[$key.'_contains_vite_css'] = collect($result['vite_css_files'])
                    ->contains(fn (string $cssFile): bool => str_contains($content, $cssFile));
            } catch (Throwable $exception) {
                $result[$key.'_render_error'] = $exception::class.': '.$exception->getMessage();
            }
        }

        if (($result['vite_css_files'][0] ?? null) !== null) {
            try {
                $request = Request::create($result['vite_css_files'][0], 'GET');
                $response = $app->handle($request);
                $contentType = (string) $response->headers->get('content-type');

                $result['vite_css_route_status'] = $response->getStatusCode();
                $result['vite_css_route_content_type'] = $contentType;
                $result['vite_css_route_ok'] = $response->getStatusCode() === 200
                    && str_contains($contentType, 'text/css');
            } catch (Throwable $exception) {
                $result['vite_css_route_error'] = $exception::class.': '.$exception->getMessage();
            }
        }
    }
} catch (Throwable $exception) {
    $result['laravel_error'] = $exception::class.': '.$exception->getMessage();
}

echo json_encode($result, JSON_PRETTY_PRINT);
