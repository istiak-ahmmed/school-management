<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPanelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard');
        }

        if ($user && $user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        }

        return $next($request);
    }
}
