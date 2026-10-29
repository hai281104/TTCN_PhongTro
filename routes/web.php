<?php

use App\Http\Controllers\DangNhapController;
use App\Http\Controllers\HopDongController;
use App\Http\Controllers\KhachThueController;
use App\Http\Controllers\PhongTroController;
use App\Http\Controllers\TongQuanController;
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
        Route::get('/khach-thue/phong-cua-toi', [TongQuanController::class, 'phongCuaToi'])->name('khach-thue.phong-cua-toi');
    });

    Route::middleware('vaiTro:CHU_TRO')->group(function () {
        Route::get('/tong-quan', [TongQuanController::class, 'tongQuanChuTro'])->name('tong-quan.chu-tro');

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

        Route::prefix('hop-dong')->name('hop-dong.')->where(['maHopDong' => '[0-9]+'])->group(function () {
            Route::get('/', [HopDongController::class, 'danhSach'])->name('danh-sach');
            Route::post('/', [HopDongController::class, 'taoMoi'])->name('tao-moi');
            Route::get('/{maHopDong}', [HopDongController::class, 'chiTiet'])->name('chi-tiet');
            Route::post('/{maHopDong}/nguoi-o-cung', [HopDongController::class, 'themNguoiOCung'])->name('them-nguoi-o-cung');
            Route::delete('/{maHopDong}/nguoi-o-cung/{maNguoiO}', [HopDongController::class, 'xoaNguoiOCung'])->where(['maNguoiO' => '[0-9]+'])->name('xoa-nguoi-o-cung');
            Route::patch('/{maHopDong}/ket-thuc', [HopDongController::class, 'ketThuc'])->name('ket-thuc');
        });
    });
});
