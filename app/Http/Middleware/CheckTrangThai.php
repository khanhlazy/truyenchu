<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTrangThai
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->dangHoatDong()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/dang-nhap')->with('loi', 'Tài khoản của bạn đã bị khóa.');
        }

        return $next($request);
    }
}
