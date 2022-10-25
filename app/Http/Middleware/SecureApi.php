<?php

namespace App\Http\Middleware;

use Closure;

class SecureApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key1 = setting('api_key', config('fixtures.api_key'));
        if ($key1 && setting('api_key_enabled', false)) {
            $key2 = $request->header('X-API-Key');
            if (empty($key2) || $key1 !== $key2) {
                return response('', 400);
            }
        }

        return $next($request);
    }
}
