<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $nguoiDung = Auth::guard($guard)->user();
                if ($nguoiDung && method_exists($nguoiDung, 'laChuTro') && $nguoiDung->laChuTro()) {
                    return redirect('/phong-tro');
                }
                return redirect('/khach-thue/phong-cua-toi');
            }
        }

        return $next($request);
    }
}
