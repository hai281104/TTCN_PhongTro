<?php

namespace App\Http\Controllers;

use App\Http\Requests\LuuNguoiOCungRequest;
use App\Models\HopDong;
use App\Models\KhachThue;
use App\Models\NguoiOCung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NguoiOCungController extends Controller
{
    // Danh sách người ở cùng đang ở, nhóm theo hợp đồng hiệu lực.
    public function danhSach(Request $yeuCau)
    {
        NguoiOCung::damBaoCauTrucBang();

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
                    ->orWhereHas('danhSachNguoiOCung', function ($q) use ($tuKhoa) {
                        $q->where('hoTen', 'like', "%{$tuKhoa}%")
                            ->orWhere('soCCCD', 'like', "%{$tuKhoa}%")
                            ->orWhere('soDienThoai', 'like', "%{$tuKhoa}%");
                    });
            });
        }

        $danhSachHopDong = $truyVan->orderBy('maHopDong', 'desc')->paginate(15)->withQueryString();
        $danhSachHopDongChon = HopDong::with(['phongTro', 'khachThue', 'danhSachNguoiOCung'])
            ->where('trangThaiHD', 'HIEU_LUC')
            ->get()
            ->sortBy(fn ($hd) => optional($hd->phongTro)->soPhong)
            ->values();

        $tongNguoiOCung = NguoiOCung::where('dangHoatDong', 1)
            ->whereHas('hopDong', fn ($q) => $q->where('trangThaiHD', 'HIEU_LUC'))
            ->count();
        $soHopDongHieuLuc = HopDong::where('trangThaiHD', 'HIEU_LUC')->count();
        $soPhongConCho = $danhSachHopDongChon
            ->filter(fn ($hd) => (1 + $hd->danhSachNguoiOCung->count()) < (optional($hd->phongTro)->soNguoiToiDa ?? 1))
            ->count();

        return view('quan-ly-khach-o-cung.danh-sach', compact(
            'danhSachHopDong',
            'danhSachHopDongChon',
            'tongNguoiOCung',
            'soHopDongHieuLuc',
            'soPhongConCho'
        ));
    }

    // Thêm một khách ở cùng nhập thủ công vào hợp đồng đang hiệu lực.
    public function taoMoi(LuuNguoiOCungRequest $yeuCau)
    {
        NguoiOCung::damBaoCauTrucBang();

        $nguoiOCung = DB::transaction(function () use ($yeuCau) {
            $hoTen = $yeuCau->filled('hoTen') ? trim($yeuCau->input('hoTen')) : null;
            $soDienThoai = $yeuCau->filled('soDienThoai') ? trim($yeuCau->input('soDienThoai')) : null;
            $soCCCD = $yeuCau->filled('soCCCD') ? trim($yeuCau->input('soCCCD')) : null;

            if (!$hoTen && $yeuCau->filled('maKhach')) {
                $khach = KhachThue::find($yeuCau->input('maKhach'));
                if ($khach) {
                    $hoTen = $khach->hoTen;
                    $soDienThoai = $soDienThoai ?: $khach->soDienThoai;
                    $soCCCD = $soCCCD ?: $khach->soCCCD;
                }
            }

            $ban = NguoiOCung::create([
                'maHopDong' => $yeuCau->input('maHopDong'),
                'maKhach' => $yeuCau->input('maKhach'),
                'hoTen' => $hoTen,
                'soDienThoai' => $soDienThoai,
                'soCCCD' => $soCCCD,
                'ngayVaoO' => $yeuCau->input('ngayVaoO') ?: now()->toDateString(),
                'ghiChu' => $yeuCau->filled('ghiChu') ? trim($yeuCau->input('ghiChu')) : 'Bạn cùng phòng',
                'dangHoatDong' => 1,
            ]);

            HopDong::where('maHopDong', $ban->maHopDong)->increment('soLuongNguoi');

            return $ban->load('khachThue', 'hopDong.phongTro');
        });

        $thongDiep = "Đã thêm {$nguoiOCung->tenHienThi} vào ở cùng phòng " . optional($nguoiOCung->hopDong->phongTro)->soPhong . '.';

        return back()->with('thongBaoThanhCong', $thongDiep);
    }

    // Cập nhật thông tin người ở cùng (họ tên, SĐT, CCCD, quan hệ, ngày vào ở).
    public function capNhat(Request $yeuCau, $maNguoiOCung)
    {
        NguoiOCung::damBaoCauTrucBang();

        $nguoiOCung = NguoiOCung::with('hopDong.phongTro')->where('dangHoatDong', 1)->findOrFail($maNguoiOCung);

        $duLieu = $yeuCau->validate([
            'hoTen' => ['required', 'string', 'max:100'],
            'soDienThoai' => ['nullable', 'string', 'max:20'],
            'soCCCD' => ['nullable', 'string', 'max:20'],
            'ngayVaoO' => ['nullable', 'date'],
            'ghiChu' => ['nullable', 'string', 'max:100'],
        ], [
            'hoTen.required' => 'Vui lòng nhập họ và tên người ở cùng.',
        ]);

        $nguoiOCung->update([
            'hoTen' => trim($duLieu['hoTen']),
            'soDienThoai' => isset($duLieu['soDienThoai']) ? trim($duLieu['soDienThoai']) : null,
            'soCCCD' => isset($duLieu['soCCCD']) ? trim($duLieu['soCCCD']) : null,
            'ngayVaoO' => $duLieu['ngayVaoO'] ?: $nguoiOCung->ngayVaoO,
            'ghiChu' => !empty($duLieu['ghiChu']) ? trim($duLieu['ghiChu']) : 'Bạn cùng phòng',
        ]);

        return back()->with('thongBaoThanhCong', "Đã cập nhật thông tin khách ở cùng '{$nguoiOCung->hoTen}'.");
    }

    // Cho một người ở cùng rời phòng (giữ lại lịch sử, giảm số người trong hợp đồng).
    public function roiPhong($maNguoiOCung)
    {
        NguoiOCung::damBaoCauTrucBang();

        $nguoiOCung = NguoiOCung::with('khachThue', 'hopDong.phongTro')
            ->where('dangHoatDong', 1)
            ->findOrFail($maNguoiOCung);

        $tenHienThi = $nguoiOCung->tenHienThi;

        DB::transaction(function () use ($nguoiOCung) {
            $nguoiOCung->update(['dangHoatDong' => 0]);
            HopDong::where('maHopDong', $nguoiOCung->maHopDong)
                ->where('soLuongNguoi', '>', 1)
                ->decrement('soLuongNguoi');
        });

        $thongDiep = "{$tenHienThi} đã rời phòng " . optional($nguoiOCung->hopDong->phongTro)->soPhong . '.';

        return back()->with('thongBaoThanhCong', $thongDiep);
    }
}
