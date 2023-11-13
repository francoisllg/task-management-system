<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use App\Traits\Http\Controllers\Api\ApiResponserTrait;
class Authenticate extends Middleware
{
    use ApiResponserTrait;
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : url('api/v1/login');
    }
}
