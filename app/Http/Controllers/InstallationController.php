<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstallationRequest;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Console\Output\BufferedOutput;

class InstallationController extends Controller
{
    static $extensions = [
        'bcmath', 'ctype', 'fileinfo', 'gd', 'json', 'mbstring', 'openssl', 'pdo', 'tokenizer', 'xml',
    ];

    static $memory = '128M';

    static $php = '7.2.0';

    static $timeout = 300;

    static $uploads = '50M';

    static $writable = [
        'bootstrap/cache',
        'storage',
        '.env',
    ];

    public function install()
    {
        if (config('fixtures.install_done')) {
            return redirect()->route('home');
        }
        $output = new BufferedOutput();
        try {
            $code = Artisan::call('migrate', ['--force' => true, '--seed' => true], $output);
            if ($code === 0) {
                Artisan::call('storage:link', [], $output);
            }
        } catch (\Exception $e) {
            logger()->error($e);
            $code = -1;
        }
        if ($code === 0) {
            touch(storage_path('.installed'));
            $code = Artisan::call('config:cache', [], $output);
            if (function_exists('opcache_reset')) {
                /** @noinspection PhpComposerExtensionStubsInspection */
                opcache_reset();
            }
        }
        if ($code !== 0) {
            flash()->error(__('The installation attempt exited with failure. Please check logs for more information.'));
            return redirect()->route('installation.show')
                ->withInput(['tab' => 'configure']);
        }
        return view('installation.success');
    }

    public function show()
    {
        if (config('fixtures.install_done')) {
            return redirect()->route('home');
        }
        $status['php'] = version_compare(PHP_VERSION, self::$php, '>=');
        foreach (self::$writable as $path) {
            $status['writable:'.$path] = is_writeable(base_path($path));
        }
        foreach (self::$extensions as $extension) {
            if ($extension === 'pdo') {
                $status['ext:pdo'] = class_exists('PDO');
            } else {
                $status['ext:'.$extension] = extension_loaded($extension);
            }
        }
        $time = (int) ini_get('max_execution_time');
        $status['ini:max_execution_time'] = $time === 0 || $time >= self::$timeout;
        $time = (int) ini_get('max_input_time');
        $status['ini:max_input_time'] = $time <= -1 || $time >= self::$timeout;
        $status['ini:memory_limit'] = str_to_bytes(ini_get('memory_limit')) >= str_to_bytes(self::$memory);
        $status['ini:post_max_size'] = str_to_bytes(ini_get('post_max_size')) >= str_to_bytes(self::$uploads);
        $status['ini:upload_max_filesize'] =
            str_to_bytes(ini_get('upload_max_filesize')) >= str_to_bytes(self::$uploads);
        $status['func:symlink'] = !is_function_enabled('symlink');
        return view('installation.show', [
            'requirements' => [
                'extensions' => self::$extensions,
                'php' => self::$php,
                'memory' => self::$memory,
                'timeout' => self::$timeout,
                'uploads' => self::$uploads,
                'writable' => self::$writable,
            ],
            'status' => $status,
        ]);
    }

    public function submit(InstallationRequest $request)
    {
        if (config('fixtures.install_done')) {
            return redirect()->route('home');
        }
        $data = $request->validated();
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s',
                $data['db_host'],
                $data['db_port'],
                $data['db_database']);
            /** @noinspection PhpComposerExtensionStubsInspection */
            new \PDO($dsn, $data['db_username'], $data['db_password']);
        } /** @noinspection PhpComposerExtensionStubsInspection */ catch (\PDOException $e) {
            throw ValidationException::withMessages([
                'db_database' => $e->getMessage(),
            ]);
        }
        $variables = [
            'APP_NAME' => config('app.name'),
            'APP_KEY' => 'base64:'.base64_encode(Encrypter::generateKey(config('app.cipher'))),
            'APP_TIMEZONE' => $data['app_timezone'],
            'APP_URL' => rtrim(url('/'), '/'),
            'DASHBOARD_STATISTICS' => true,
            'DB_CONNECTION' => $data['db_connection'],
            'DB_HOST' => $data['db_host'],
            'DB_PORT' => $data['db_port'],
            'DB_DATABASE' => $data['db_database'],
            'DB_USERNAME' => $data['db_username'],
            'DB_PASSWORD' => $data['db_password'],
        ];
        $environment = '';
        foreach ($variables as $key => $value) {
            if ($value === null) {
                $value = 'null';
            } else if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            if (strpos($value, ' ') !== false || strpos($value, 'PASSWORD') !== false) {
                $value = '"'.$value.'"';
            }
            $environment .= "$key=$value\n";
        }
        file_put_contents(base_path('.env'), $environment);
        return redirect()->route('installation.install');
    }
}
