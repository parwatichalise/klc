<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // If user is authenticated but not an admin, redirect to the student page
        if (Auth::check() && Auth::user()->role === 'user') {
            return redirect()->route('quizzes.create');
        }


        return redirect()->route('login')->withErrors([
            'email' => 'You do not have access to this section.',
        ]);
        return $next($request);
    }
}
