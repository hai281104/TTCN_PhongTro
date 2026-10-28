<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class DangNhapController extends Controller
{
    public function hienThiForm()
    {
        if (Auth::check()) {
            return $this->dieuHuongTheoVaiTro(Auth::user());
        }

        return view('xac-thuc.dang-nhap');
    }

    public function xuLyDangNhap(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'matKhau' => ['required', 'string', 'min:6'],
            'ghiNho' => ['nullable'],
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'matKhau.required' => 'Vui lòng nhập mật khẩu.',
            'matKhau.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
        ]);

        $khoaGioiHan = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($khoaGioiHan, 5)) {
            $giayConLai = RateLimiter::availableIn($khoaGioiHan);
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Bạn đã thử đăng nhập quá nhiều lần. Vui lòng thử lại sau {$giayConLai} giây.",
                ]);
        }

        $thongTinXacThuc = [
            'email' => $request->input('email'),
            'password' => $request->input('matKhau'),
        ];
        $coGhiNho = $request->boolean('ghiNho');

        if (!Auth::attempt($thongTinXacThuc, $coGhiNho)) {
            RateLimiter::hit($khoaGioiHan, 60);
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email hoặc mật khẩu không chính xác.',
                ]);
        }

        $nguoiDung = Auth::user();

        if (!$nguoiDung->dangHoatDong) {
            Auth::logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa hoặc ngừng hoạt động.',
                ]);
        }

        RateLimiter::clear($khoaGioiHan);
        $request->session()->regenerate();

        return $this->dieuHuongTheoVaiTro($nguoiDung);
    }

    public function dangXuat(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dang-nhap.hien-thi')
            ->with('thongBaoThanhCong', 'Đã đăng xuất thành công.');
    }

    protected function dieuHuongTheoVaiTro($nguoiDung)
    {
        if ($nguoiDung->laChuTro()) {
            return redirect()->intended(
                Route::has('phong-tro.danh-sach') ? route('phong-tro.danh-sach') : '/phong-tro'
            );
        }

        return redirect()->intended(
            Route::has('khach-thue.phong-cua-toi') ? route('khach-thue.phong-cua-toi') : '/khach-thue/phong-cua-toi'
        );
    }
}
