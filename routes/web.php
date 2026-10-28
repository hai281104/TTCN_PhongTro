<?php

use App\Http\Controllers\DangNhapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dang-nhap.hien-thi');
});

Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [DangNhapController::class, 'hienThiForm'])->name('dang-nhap.hien-thi');
    Route::post('/dang-nhap', [DangNhapController::class, 'xuLyDangNhap'])->name('dang-nhap.xu-ly');
    Route::get('/login', fn() => redirect()->route('dang-nhap.hien-thi'))->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/dang-xuat', [DangNhapController::class, 'dangXuat'])->name('dang-xuat');

    Route::middleware('vaiTro:CHU_TRO')->group(function () {
        Route::get('/phong-tro', function () {
            return view('tong-quan-chu-tro');
        })->name('phong-tro.danh-sach');
    });

    Route::middleware('vaiTro:KHACH_THUE')->group(function () {
        Route::get('/khach-thue/phong-cua-toi', function () {
            return view('tong-quan-khach-thue');
        })->name('khach-thue.phong-cua-toi');
    });
});
