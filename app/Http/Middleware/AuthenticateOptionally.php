<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateOptionally extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ($request->hasHeader('Authorization')) {
            return parent::handle($request, $next, ...$guards);
        }

        return $next($request);
    }
}
