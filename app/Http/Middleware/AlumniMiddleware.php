<?php

namespace App\Http\Middleware;

use App\GlobalVariable;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AlumniMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (User::checkAuth() == false || User::checkAuth() == null) {
            return redirect()->route('system.config');
        }

        session_start();
        $role_id = Session::get('role_id');
        if ($role_id == GlobalVariable::isAlumni()) {
            return $next($request);
        } elseif ($role_id != "") {
            return redirect('alumni-dashboard');
        } else {
            return redirect('login');
        }
    }
}
