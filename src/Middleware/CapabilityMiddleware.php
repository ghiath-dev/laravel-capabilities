<?php

namespace Holoultek\Capabilities\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CapabilityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->isChief()) return $next($request);

        if (!$request->route()->controller instanceof Closure) abort(403);

        $controller = $request->route()->getController();
        $controller = get_class($controller);
        $controller = Str::afterLast($controller, '\\');
        $controller = str_replace('Controller', '', $controller);
        $method = $request->route()->getActionMethod();
        return $request->user()->hasAbility($controller, $method) ? $next($request) : abort(403);
    }
}
