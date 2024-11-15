<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        
        // Memastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect('/login'); // Atau halaman login yang sesuai
        }

        // Memeriksa apakah pengguna memiliki role yang sesuai
        if (Auth::user()->role !== $role) {
            // abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
           return redirect()->back();
        }

        return $next($request);
    }
}
