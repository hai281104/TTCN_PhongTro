<?php

use App\Http\Controllers\DangNhapController;
use App\Http\Controllers\KhachThueController;
use App\Http\Controllers\PhongTroController;
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

    Route::middleware('vaiTro:KHACH_THUE')->group(function () {
        Route::get('/khach-thue/phong-cua-toi', function () {
            return view('tong-quan-khach-thue');
        })->name('khach-thue.phong-cua-toi');
    });

    Route::middleware('vaiTro:CHU_TRO')->group(function () {
        Route::get('/tong-quan', function () {
            return view('tong-quan-chu-tro');
        })->name('tong-quan.chu-tro');

        Route::prefix('phong-tro')->name('phong-tro.')->where(['maPhong' => '[0-9]+'])->group(function () {
            Route::get('/', [PhongTroController::class, 'danhSach'])->name('danh-sach');
            Route::post('/', [PhongTroController::class, 'taoMoi'])->name('tao-moi');
            Route::get('/{maPhong}', [PhongTroController::class, 'chiTiet'])->name('chi-tiet');
            Route::put('/{maPhong}', [PhongTroController::class, 'capNhat'])->name('cap-nhat');
            Route::patch('/{maPhong}/trang-thai', [PhongTroController::class, 'doiTrangThai'])->name('doi-trang-thai');
            Route::delete('/{maPhong}', [PhongTroController::class, 'xoaMem'])->name('xoa-mem');
            Route::patch('/{maPhong}/khoi-phuc', [PhongTroController::class, 'khoiPhuc'])->name('khoi-phuc');
        });

        Route::prefix('khach-thue')->name('khach-thue.')->where(['maKhach' => '[0-9]+'])->group(function () {
            Route::get('/', [KhachThueController::class, 'danhSach'])->name('danh-sach');
            Route::post('/', [KhachThueController::class, 'taoMoi'])->name('tao-moi');
            Route::get('/{maKhach}', [KhachThueController::class, 'chiTiet'])->name('chi-tiet');
            Route::put('/{maKhach}', [KhachThueController::class, 'capNhat'])->name('cap-nhat');
            Route::post('/{maKhach}/cap-tai-khoan', [KhachThueController::class, 'capTaiKhoan'])->name('cap-tai-khoan');
            Route::delete('/{maKhach}', [KhachThueController::class, 'xoaMem'])->name('xoa-mem');
            Route::patch('/{maKhach}/khoi-phuc', [KhachThueController::class, 'khoiPhuc'])->name('khoi-phuc');
        });
    });
});
