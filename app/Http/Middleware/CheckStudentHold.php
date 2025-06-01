<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStudentHold
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->hasRole('student')) {
            $student = Auth::user()->student; // Assuming User has a hasOne relationship to Student

            if ($student && $student->is_on_hold) {
                // If it's an API request, return JSON error
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Your account is on hold. Please resolve the hold to access this service.'], 403);
                }

                // For web requests, redirect to the hold status page with an error message
                return redirect()->route('student.holds')->with('error', 'Your account is on hold. Please resolve the hold to access this service.');
            }
        }

        return $next($request);
    }
}
