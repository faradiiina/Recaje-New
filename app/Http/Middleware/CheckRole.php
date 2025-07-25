<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            if ($request->user() && $request->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }
        
        return $next($request);
    }
}
