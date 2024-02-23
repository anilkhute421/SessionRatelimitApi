<?php

namespace App\Http\Middleware;

use Closure;
// use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RateLimitSessions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $sessionUniqueId = session()->getId();
        $ipFound = DB::table('sessions')->where('ip_address', $request->ip())->first();
        if (!blank($ipFound)) {
            if ($ipFound->id == $sessionUniqueId) {
                return response()->view('welcome', ['message' => 'Allowed Old session found, keep going  ' . $sessionUniqueId]);
            } else {
                return response()->view('warning', ['message' => 'Sorry Previous session already in progress with same IP, so flush it: ' . $sessionUniqueId]);
            }
        } else {
            return $next($request);
        }
    }
}
