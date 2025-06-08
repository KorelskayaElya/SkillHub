<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return null;
        }
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
}
