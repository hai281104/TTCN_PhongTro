<?php

namespace App\Http\Controllers;

use App\Http\Requests\LuuNguoiOCungRequest;
use App\Models\HopDong;
use App\Models\KhachThue;
use App\Models\NguoiOCung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Bước 4 của quy trình nghiệp vụ: Quản lý khách ở cùng.
 * Phòng (1) -> Hồ sơ khách (2) -> Hợp đồng (3) -> Khách ở cùng (4).
 */
class NguoiOCungController extends Controller
{
    // Danh sách người ở cùng đang ở, nhóm theo hợp đồng hiệu lực.
    public function danhSach(Request $yeuCau)
    {
        $truyVan = HopDong::with([
            'phongTro.loaiPhong',
            'khachThue',
            'danhSachNguoiOCung.khachThue',
        ])->where('trangThaiHD', 'HIEU_LUC');

        if ($yeuCau->filled('tuKhoa')) {
            $tuKhoa = trim($yeuCau->input('tuKhoa'));
            $truyVan->where(function ($sub) use ($tuKhoa) {
                $sub->whereHas('phongTro', fn ($q) => $q->where('soPhong', 'like', "%{$tuKhoa}%"))
                    ->orWhereHas('khachThue', fn ($q) => $q->where('hoTen', 'like', "%{$tuKhoa}%"))
                    ->orWhereHas('danhSachNguoiOCung.khachThue', function ($q) use ($tuKhoa) {
                        $q->where('hoTen', 'like', "%{$tuKhoa}%")
                            ->orWhere('soCCCD', 'like', "%{$tuKhoa}%")
                            ->orWhere('soDienThoai', 'like', "%{$tuKhoa}%");
                    });
            });
        }

        $danhSachHopDong = $truyVan->get()->sortBy(fn ($hd) => optional($hd->phongTro)->soPhong)->values();

        $danhSachKhachCoTheThem = KhachThue::chuaOPhongNao()
            ->orderBy('hoTen')
            ->get(['maKhach', 'hoTen', 'soCCCD', 'soDienThoai']);

        $tongNguoiOCung = NguoiOCung::where('dangHoatDong', 1)
            ->whereHas('hopDong', fn ($q) => $q->where('trangThaiHD', 'HIEU_LUC'))
            ->count();
        $soHopDongHieuLuc = HopDong::where('trangThaiHD', 'HIEU_LUC')->count();
        $soPhongConCho = HopDong::with(['phongTro', 'danhSachNguoiOCung'])
            ->where('trangThaiHD', 'HIEU_LUC')->get()
            ->filter(fn ($hd) => (1 + $hd->danhSachNguoiOCung->count()) < (optional($hd->phongTro)->soNguoiToiDa ?? 1))
            ->count();

        return view('quan-ly-khach-o-cung.danh-sach', compact(
            'danhSachHopDong',
            'danhSachKhachCoTheThem',
            'tongNguoiOCung',
            'soHopDongHieuLuc',
            'soPhongConCho'
        ));
    }

    // Thêm một khách (đã có hồ sơ) vào ở cùng trong hợp đồng đang hiệu lực.
    public function taoMoi(LuuNguoiOCungRequest $yeuCau)
    {
        $nguoiOCung = DB::transaction(function () use ($yeuCau) {
            $ban = NguoiOCung::create([
                'maHopDong' => $yeuCau->input('maHopDong'),
                'maKhach' => $yeuCau->input('maKhach'),
                'ngayVaoO' => $yeuCau->input('ngayVaoO') ?: now()->toDateString(),
                'ghiChu' => $yeuCau->filled('ghiChu') ? trim($yeuCau->input('ghiChu')) : 'Ở cùng',
                'dangHoatDong' => 1,
            ]);

            HopDong::where('maHopDong', $ban->maHopDong)->increment('soLuongNguoi');

            return $ban->load('khachThue', 'hopDong.phongTro');
        });

        $thongDiep = "Đã thêm {$nguoiOCung->khachThue->hoTen} vào ở cùng phòng " . optional($nguoiOCung->hopDong->phongTro)->soPhong . '.';

        return back()->with('thongBaoThanhCong', $thongDiep);
    }

    // Cho một người ở cùng rời phòng (giữ lại lịch sử, giảm số người trong hợp đồng).
    public function roiPhong($maNguoiOCung)
    {
        $nguoiOCung = NguoiOCung::with('khachThue', 'hopDong.phongTro')
            ->where('dangHoatDong', 1)
            ->findOrFail($maNguoiOCung);

        DB::transaction(function () use ($nguoiOCung) {
            $nguoiOCung->update(['dangHoatDong' => 0]);
            HopDong::where('maHopDong', $nguoiOCung->maHopDong)
                ->where('soLuongNguoi', '>', 1)
                ->decrement('soLuongNguoi');
        });

        $thongDiep = "{$nguoiOCung->khachThue->hoTen} đã rời phòng " . optional($nguoiOCung->hopDong->phongTro)->soPhong . '.';

        return back()->with('thongBaoThanhCong', $thongDiep);
    }
}

