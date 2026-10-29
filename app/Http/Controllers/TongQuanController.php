<?php

namespace App\Http\Controllers;

use App\Models\ChiSoDienNuoc;
use App\Models\HoaDon;
use App\Models\HopDong;
use App\Models\KhachThue;
use App\Models\PhongTro;
use Illuminate\Support\Facades\Auth;

class TongQuanController extends Controller
{
    // Hiển thị bảng điều khiển tổng quan dành cho chủ trọ.
    public function tongQuanChuTro()
    {
        $tongSoPhong = PhongTro::where('dangHoatDong', 1)->count();
        $phongDangThue = PhongTro::where('dangHoatDong', 1)->where('trangThaiPhong', 'DANG_THUE')->count();
        $phongTrong = PhongTro::where('dangHoatDong', 1)->where('trangThaiPhong', 'TRONG')->count();
        $phongSuaChua = PhongTro::where('dangHoatDong', 1)->where('trangThaiPhong', 'DANG_SUA_CHUA')->count();
        $tongKhachThue = KhachThue::where('dangHoatDong', 1)->count();

        // Thống kê hóa đơn và công nợ chưa thu
        $tongTienNo = HoaDon::where('trangThaiThanhToan', 'CON_NO')->sum('tongTien');
        $soHoaDonNo = HoaDon::where('trangThaiThanhToan', 'CON_NO')->count();

        // Danh sách các phòng gần nhất để chủ trọ theo dõi nhanh
        $danhSachPhongGanDay = PhongTro::where('dangHoatDong', 1)
            ->with(['hopDongHieuLuc.khachThue'])
            ->orderBy('soPhong')
            ->limit(8)
            ->get();

        return view('tong-quan-chu-tro', compact(
            'tongSoPhong',
            'phongDangThue',
            'phongTrong',
            'phongSuaChua',
            'tongKhachThue',
            'tongTienNo',
            'soHoaDonNo',
            'danhSachPhongGanDay'
        ));
    }

    // Hiển thị thông tin phòng và hợp đồng của khách thuê đang đăng nhập.
    public function phongCuaToi()
    {
        $nguoiDung = Auth::user();

        // Tìm hồ sơ khách thuê tương ứng với tài khoản đăng nhập hiện tại
        $khachThue = KhachThue::where('maNguoiDung', $nguoiDung->maNguoiDung)->first();

        $hopDong = null;
        $phongTro = null;
        $danhSachNguoiOCung = collect();
        $danhSachHoaDon = collect();
        $chiSoDienNuocMoiNhat = null;
        $hoaDonChuaThanhToan = null;

        if ($khachThue) {
            // Lấy hợp đồng hiệu lực kèm phòng trọ, danh sách người ở cùng và các hóa đơn
            $hopDong = HopDong::with([
                'phongTro',
                'nguoiOCung',
                'hoaDon' => function ($truyVan) {
                    $truyVan->with('chiTietHoaDon.dichVu')->orderByDesc('thangNam');
                }
            ])
            ->where('maKhach', $khachThue->maKhach)
            ->where('trangThaiHD', 'HIEU_LUC')
            ->first();

            if ($hopDong) {
                $phongTro = $hopDong->phongTro;
                $danhSachNguoiOCung = $hopDong->nguoiOCung;
                $danhSachHoaDon = $hopDong->hoaDon;
                $hoaDonChuaThanhToan = $danhSachHoaDon->where('trangThaiThanhToan', 'CON_NO')->first();

                // Lấy chỉ số điện nước mới nhất của phòng
                if ($phongTro) {
                    $chiSoDienNuocMoiNhat = ChiSoDienNuoc::where('maPhong', $phongTro->maPhong)
                        ->orderByDesc('thangGhiNhan')
                        ->first();
                }
            }
        }

        return view('tong-quan-khach-thue', compact(
            'khachThue',
            'hopDong',
            'phongTro',
            'danhSachNguoiOCung',
            'danhSachHoaDon',
            'chiSoDienNuocMoiNhat',
            'hoaDonChuaThanhToan'
        ));
    }
}
