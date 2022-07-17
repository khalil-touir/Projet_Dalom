<?php

namespace App\Http\Middleware;

use App\Enums\HTTPHeader;
use Closure;
use Illuminate\Http\Request;

class IsSupplier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() && auth()->user()->role == config('global.roles.supplier', 'supplier')) {
            return $next($request);
        } else {
            abort(HTTPHeader::FORBIDDEN, 'you need supplier role');
        }
    }
}
