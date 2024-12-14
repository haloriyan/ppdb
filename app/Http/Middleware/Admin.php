<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\UrlGenerator;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $myData = Auth::guard('admin')->user();
        if ($myData == "") {
            return redirect()->route('admin.login', [
                'r' => url()->current()
            ])->withErrors(['Login dahulu sebelum melanjutkan']);
        }
        return $next($request);
    }
}
