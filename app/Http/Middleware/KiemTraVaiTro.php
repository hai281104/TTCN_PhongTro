<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KiemTraVaiTro
{
    public function handle(Request $request, Closure $next, ...$danhSachVaiTro)
    {
        if (!Auth::check()) {
            return redirect()->route('dang-nhap.hien-thi');
        }

        $nguoiDung = Auth::user();

        if (!$nguoiDung->dangHoatDong) {
            Auth::logout();
            return redirect()->route('dang-nhap.hien-thi')
                ->withErrors(['email' => 'Tài khoản của bạn đã bị khóa hoặc ngừng hoạt động.']);
        }

        if (empty($danhSachVaiTro) || in_array($nguoiDung->vaiTro, $danhSachVaiTro, true)) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập vào trang này.');
    }
}
