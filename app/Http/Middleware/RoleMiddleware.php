<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{

    public function handle(Request $request, Closure $next, ...$roles): Response
    {

        // cek apakah user sudah login
        if (!Auth::check()) {
            // Jika belum login, langsung usir ke halaman login.
            return redirect('login');
        }
        
        // Ambil data lengkap pengguna yang sedang login.
        $user = Auth::user();

        // ATURAN 2: Cocokkan role pengguna dengan daftar yang diizinkan.
        foreach ($roles as $role) {
            
            if ($user->role === $role) {
                return $next($request);
            }
        }

        return redirect('/dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}