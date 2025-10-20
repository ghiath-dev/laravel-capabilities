<?php

namespace Holoultek\Capabilities\Middleware;

use Closure;
use Holoultek\Capabilities\Services\UserContextManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CapabilitiesContextMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!UserContextManager::isInitialized()) {
            UserContextManager::initialize();
        }

        return $next($request);
    }
}
