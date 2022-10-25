<?php

namespace App\Http\Controllers;

use App\Rules\PurchaseCode;
use App\User;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class InstallController extends Controller
{
    static $directories = [
        'bootstrap/cache',
        'storage',
    ];

    static $extensions = [
        'bcmath', 'ctype', 'fileinfo', 'gd', 'json', 'mbstring', 'openssl', 'pdo', 'tokenizer', 'xml',
    ];

    static $memory = '128M';

    static $php = '7.2.0';

    static $rules = [
        'GOOGLE_APPLICATION_CREDENTIALS' => ['nullable', 'required_if:FILESYSTEM_CLOUD,gcs', 'file'],
        'GOOGLE_CLOUD_PROJECT_ID' => ['nullable', 'required_if:FILESYSTEM_CLOUD,gcs', 'string', 'max:255'],
        'GOOGLE_CLOUD_STORAGE_BUCKET' => ['nullable', 'required_if:FILESYSTEM_CLOUD,gcs', 'string', 'max:255'],
        'FACEBOOK_APP_ID' => ['nullable', 'string', 'max:255'],
        'FACEBOOK_APP_SECRET' => ['nullable', 'string', 'max:255'],
        'FIREBASE_CREDENTIALS' => ['required', 'file'],
        'GOOGLE_CLIENT_ID' => ['nullable', 'string', 'max:255'],
        'OTP_SERVICE' => ['nullable', 'string', 'in:firebase,twilio,msg91'],
        'MSG91_AUTH_KEY' => ['nullable', 'required_if:OTP_SERVICE,msg91', 'string', 'max:255'],
        'TWILIO_SID' => ['nullable', 'required_if:OTP_SERVICE,twilio', 'string', 'max:255'],
        'TWILIO_AUTH_TOKEN' => ['nullable', 'required_if:OTP_SERVICE,twilio', 'string', 'max:255'],
        'TWILIO_VERIFY_SID' => ['nullable', 'required_if:OTP_SERVICE,twilio', 'string', 'max:255'],
    ];

    static $timeout = 300;

    static $uploads = '50M';

    public function overview()
    {
        self::check();
        $status['php'] = version_compare(PHP_VERSION, self::$php, '>=');
        foreach (self::$directories as $dir) {
            $status['directory:' . $dir] = is_dir(base_path($dir)) && is_writeable(base_path($dir));
        }

        foreach (self::$extensions as $extension) {
            if ($extension === 'pdo') {
                $status['extension:pdo'] = class_exists('PDO');
            } else {
                $status['extension:' . $extension] = extension_loaded($extension);
            }
        }

        $status['ini:max_execution_time'] = ((int)ini_get('max_execution_time')) >= self::$timeout;
        $status['ini:max_input_time'] = ((int)ini_get('max_input_time')) >= self::$timeout;
        $status['ini:memory_limit'] = self::bytes(ini_get('memory_limit')) >= self::bytes(self::$memory);
        $status['ini:post_max_size'] = self::bytes(ini_get('post_max_size')) >= self::bytes(self::$uploads);
        $status['ini:upload_max_filesize'] =
            self::bytes(ini_get('upload_max_filesize')) >= self::bytes(self::$uploads);
        return view('install.overview', [
            'requirements' => [
                'directories' => self::$directories,
                'extensions' => self::$extensions,
                'php' => self::$php,
                'memory' => self::$memory,
                'timeout' => self::$timeout,
                'uploads' => self::$uploads,
            ],
            'status' => $status,
        ]);
    }

    public function configure()
    {
        self::check();
        return view('install.configure');
    }

    public function save(Request $request)
    {
        self::check();
        $rules = [
            'PURCHASE_CODE' => ['required', 'string'],
            'APP_NAME' => ['required', 'string', 'max:255'],
            'APP_TIMEZONE' => ['required', 'string', 'timezone'],
            'APP_URL' => ['required', 'string', 'max:255'],
            'DB_HOST' => ['required', 'string', 'max:255'],
            'DB_PORT' => ['required', 'integer', 'min:0', 'max:65535'],
            'DB_DATABASE' => ['required', 'string', 'max:255'],
            'DB_USERNAME' => ['required', 'string', 'max:255'],
            'DB_PASSWORD' => ['required', 'string', 'max:255'],
            'FILESYSTEM_CLOUD' => ['required', 'string', 'in:public,s3,gcs'],
            'QUEUE_CONNECTION' => ['required', 'string', 'in:sync,redis'],
            'AWS_ACCESS_KEY_ID' => ['nullable', 'required_if:FILESYSTEM_CLOUD,s3', 'string', 'max:255'],
            'AWS_SECRET_ACCESS_KEY' => ['nullable', 'required_if:FILESYSTEM_CLOUD,s3', 'string', 'max:255'],
            'AWS_DEFAULT_REGION' => ['nullable', 'required_if:FILESYSTEM_CLOUD,s3', 'string', 'max:255'],
            'AWS_BUCKET' => ['nullable', 'required_if:FILESYSTEM_CLOUD,s3', 'string', 'max:255'],
            'AWS_ENDPOINT' => ['nullable', 'string', 'max:255'],
            'MAIL_DRIVER' => ['required', 'string', 'in:sendmail,smtp,mailgun'],
            'MAIL_HOST' => ['nullable', 'required_if:MAIL_DRIVER,smtp', 'string', 'max:255'],
            'MAIL_PORT' => ['nullable', 'required_if:MAIL_DRIVER,smtp', 'integer', 'min:1', 'max:65535'],
            'MAIL_USERNAME' => ['nullable', 'string', 'max:255'],
            'MAIL_PASSWORD' => ['nullable', 'string', 'max:255'],
            'MAIL_ENCRYPTION' => ['nullable', 'string', 'in:ssl,tls'],
            'MAILGUN_DOMAIN' => ['nullable', 'required_if:MAIL_DRIVER,mailgun', 'string', 'max:255'],
            'MAILGUN_SECRET' => ['nullable', 'required_if:MAIL_DRIVER,mailgun', 'string', 'max:255'],
        ];
        $data = $this->validate($request, $rules + self::$rules);
        /** @var UploadedFile $json */
        $json = $data['FIREBASE_CREDENTIALS'];
        $json = $json->move(storage_path(), 'firebase-credentials.json');
        $data['FIREBASE_CREDENTIALS'] = str_replace('\\', '/', $json->getRealPath());
        if ($data['FILESYSTEM_CLOUD'] === 'gcs') {
            /** @var UploadedFile $json */
            $json = $data['GOOGLE_APPLICATION_CREDENTIALS'];
            $json = $json->move(storage_path(), 'google-application-credentials.json');
            $data['GOOGLE_APPLICATION_CREDENTIALS'] = str_replace('\\', '/', $json->getRealPath());
            $data['GOOGLE_CLOUD_KEY_FILE'] = '"${GOOGLE_APPLICATION_CREDENTIALS}"';
        } else {
            $data['GOOGLE_APPLICATION_CREDENTIALS']
                = $data['GOOGLE_CLOUD_PROJECT_ID']
                = $data['GOOGLE_CLOUD_STORAGE_BUCKET']
                = $data['GOOGLE_CLOUD_KEY_FILE']
                = 'null';
        }

        if ($data['FILESYSTEM_CLOUD'] !== 's3') {
            $data['AWS_ACCESS_KEY_ID']
                = $data['AWS_SECRET_ACCESS_KEY']
                = $data['AWS_DEFAULT_REGION']
                = $data['AWS_BUCKET']
                = 'null';
        } else if (empty($data['AWS_ENDPOINT'])) {
            $data['AWS_ENDPOINT'] = 'null';
        }

        if (empty($data['OTP_SERVICE']) || $data['OTP_SERVICE'] !== 'msg91') {
            $data['MSG91_AUTH_KEY'] = 'null';
        }

        if (empty($data['OTP_SERVICE']) || $data['OTP_SERVICE'] !== 'twilio') {
            $data['TWILIO_SID'] = $data['TWILIO_AUTH_TOKEN'] = $data['TWILIO_VERIFY_SID'] = 'null';
        }

        if ($data['MAIL_DRIVER'] !== 'smtp') {
            $data['MAIL_HOST']
                = $data['MAIL_PORT']
                = $data['MAIL_USERNAME']
                = $data['MAIL_PASSWORD']
                = $data['MAIL_ENCRYPTION']
                = 'null';
        }

        if ($data['MAIL_DRIVER'] !== 'mailgun') {
            $data['MAILGUN_DOMAIN'] = $data['MAILGUN_SECRET'] = 'null';
        }

        $url = parse_url($data['APP_URL']);
        $data['APP_ENV'] = 'production';
        $data['APP_KEY'] = 'base64:' . base64_encode(Encrypter::generateKey(config('app.cipher')));
        $data['APP_DEBUG'] = 'false';
        $data['LOG_CHANNEL'] = 'stack';
        $data['DB_CONNECTION'] = 'mysql';
        $data['BROADCAST_DRIVER'] = 'log';
        $data['CACHE_DRIVER'] = 'file';
        $data['SESSION_DRIVER'] = 'file';
        $data['SESSION_LIFETIME'] = '120';
        $data['MAIL_FROM_ADDRESS'] = 'server@' . $url['host'];
        $data['MAIL_FROM_NAME'] = $data['APP_NAME'];
        $data['REDIS_HOST'] = '127.0.0.1';
        $data['REDIS_PASSWORD'] = 'null';
        $data['REDIS_PORT'] = '6379';
        $data['TELESCOPE_ENABLED'] = 'false';
        ksort($data);
        $content = '';
        foreach ($data as $key => $value) {
            if (strpos($value, ' ') !== false || strpos($value, 'PASSWORD') !== false) {
                $value = '"' . $value . '"';
            }

            $content .= "$key=$value\n";
        }

        file_put_contents(base_path('.env'), $content);
        Artisan::call('config:clear', []);
        return redirect()->route('install.finalize');
    }

    public function finalize()
    {
        self::check();
        return view('install.finalize');
    }

    public function run()
    {
        self::check();
        $code = -1;
        $output = new BufferedOutput();
        try {
            $code = $this->runOrThrow($output);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            $output->writeln($e->getTraceAsString());
        }

        if ($code === 0) {
            touch(storage_path('.installed'));
            if (function_exists('opcache_reset')) {
                /** @noinspection PhpComposerExtensionStubsInspection */
                $reset = opcache_reset();
                if ($reset) {
                    $output->writeln('OPCache was reset successfully.');
                } else {
                    $output->writeln('OPCache is currently disabled.');
                }
            }
        }

        return view('install.result', [
            'success' => $code === 0,
            'output' => $output->fetch(),
        ]);
    }

    private function runOrThrow(OutputInterface $output)
    {
        $code = Artisan::call('config:cache', [], $output);
        if ($code === 0) {
            $code = Artisan::call('storage:link', [], $output);
            if ($code === 0) {
                $code = Artisan::call('migrate', ['--force' => true], $output);
                if ($code === 0) {
                    $exists = User::query()->where('role', 'admin')->exists();
                    if (empty($exists)) {
                        $user = User::query()->create([
                            'name' => config('app.name'),
                            'username' => 'admin',
                            'password' => Hash::make($password = '12345678'),
                            'role' => 'admin',
                            'enabled' => true,
                            'verified' => true,
                        ]);
                        $output->writeln(
                            sprintf('User "%s" created with password "%s".', $user->username, $password)
                        );
                    }
                }
            }
        }

        return $code;
    }

    private static function bytes($size)
    {
        if (empty($size)) {
            return NAN;
        }

        if ($size === '-1') {
            return INF;
        }

        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }

        return round($size);
    }

    private static function check()
    {
        abort_if(is_file(storage_path('.installed')), 403);
    }
}
