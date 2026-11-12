<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapNhatPhongTroRequest;
use App\Http\Requests\LuuPhongTroRequest;
use App\Models\LoaiPhong;
use App\Models\PhongTro;
use Illuminate\Http\Request;

class PhongTroController extends Controller
{
    // Hiển thị danh sách phòng trọ kèm bộ lọc và các thẻ thống kê.
    public function danhSach(Request $yeuCau)
    {
        $truyVan = PhongTro::query()->where('dangHoatDong', 1);

        if ($yeuCau->filled('tuKhoa')) {
            $tuKhoa = trim($yeuCau->input('tuKhoa'));
            $truyVan->where('soPhong', 'like', "%{$tuKhoa}%");
        }

        if ($yeuCau->filled('maLoaiPhong')) {
            $truyVan->where('maLoaiPhong', $yeuCau->input('maLoaiPhong'));
        }

        if ($yeuCau->filled('tang')) {
            $truyVan->where('tang', $yeuCau->input('tang'));
        }

        if ($yeuCau->filled('trangThaiPhong') && in_array($yeuCau->input('trangThaiPhong'), ['TRONG', 'DANG_THUE', 'DANG_SUA_CHUA'])) {
            $truyVan->where('trangThaiPhong', $yeuCau->input('trangThaiPhong'));
        }

        if ($yeuCau->filled('giaTu')) {
            $giaTu = str_replace(['.', ','], '', $yeuCau->input('giaTu'));
            $truyVan->where('donGiaMacDinh', '>=', $giaTu);
        }

        if ($yeuCau->filled('giaDen')) {
            $giaDen = str_replace(['.', ','], '', $yeuCau->input('giaDen'));
            $truyVan->where('donGiaMacDinh', '<=', $giaDen);
        }

        $danhSachPhong = $truyVan->with(['loaiPhong', 'hopDongHieuLuc.khachThue'])
            ->orderBy('soPhong', 'asc')
            ->paginate(12)
            ->withQueryString();

        $danhSachLoaiPhong = LoaiPhong::where('dangHoatDong', 1)
            ->withCount(['phongTro' => function ($query) {
                $query->where('dangHoatDong', 1);
            }])
            ->orderBy('tenLoaiPhong', 'asc')
            ->get();
        $danhSachTienNghi = PhongTro::layDanhSachTienNghiHeThong();

        // Danh sách các tầng hiện có trong hệ thống để phục vụ bộ lọc
        $danhSachTang = PhongTro::where('dangHoatDong', 1)
            ->whereNotNull('tang')
            ->distinct()
            ->orderBy('tang', 'asc')
            ->pluck('tang');

        $tongSoPhong = PhongTro::where('dangHoatDong', 1)->count();
        $soPhongDangThue = PhongTro::where('dangHoatDong', 1)->where('trangThaiPhong', 'DANG_THUE')->count();
        $soPhongTrong = PhongTro::where('dangHoatDong', 1)->where('trangThaiPhong', 'TRONG')->count();
        $soPhongDangSuaChua = PhongTro::where('dangHoatDong', 1)->where('trangThaiPhong', 'DANG_SUA_CHUA')->count();
        $tyLeLapDay = $tongSoPhong > 0 ? round(($soPhongDangThue / $tongSoPhong) * 100, 1) : 0;

        return view('quan-ly-phong.danh-sach', compact(
            'danhSachPhong',
            'danhSachLoaiPhong',
            'danhSachTienNghi',
            'danhSachTang',
            'tongSoPhong',
            'soPhongDangThue',
            'soPhongTrong',
            'soPhongDangSuaChua',
            'tyLeLapDay'
        ));
    }

    // Lưu thông tin phòng trọ mới vào hệ thống.
    public function taoMoi(LuuPhongTroRequest $yeuCau)
    {
        $phongMoi = PhongTro::create([
            'soPhong' => trim($yeuCau->input('soPhong')),
            'maLoaiPhong' => $yeuCau->input('maLoaiPhong'),
            'tang' => $yeuCau->filled('tang') ? trim($yeuCau->input('tang')) : 'Tầng 1',
            'dienTich' => $yeuCau->filled('dienTich') ? $yeuCau->input('dienTich') : null,
            'tienCocMacDinh' => $yeuCau->filled('tienCocMacDinh') ? $yeuCau->input('tienCocMacDinh') : 0,
            'tienNghi' => $yeuCau->input('tienNghi', []),
            'soNguoiToiDa' => $yeuCau->input('soNguoiToiDa'),
            'donGiaMacDinh' => $yeuCau->input('donGiaMacDinh'),
            'trangThaiPhong' => 'TRONG',
            'ghiChu' => $yeuCau->input('ghiChu'),
            'dangHoatDong' => 1,
        ]);

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => 'Thêm phòng trọ mới thành công.',
                'duLieu' => $phongMoi->load('loaiPhong'),
            ], 201);
        }

        return redirect()->route('phong-tro.danh-sach')
            ->with('thongBaoThanhCong', "Thêm phòng {$phongMoi->soPhong} thành công.");
    }

    // Lấy dữ liệu chi tiết của một phòng trọ.
    public function chiTiet(Request $yeuCau, $maPhong)
    {
        $phongTro = PhongTro::with([
            'loaiPhong',
            'hopDongHieuLuc.khachThue',
            'chiSoDienNuoc' => function ($query) {
                $query->orderBy('thangGhiNhan', 'desc')->take(3);
            },
            'hopDong' => function ($query) {
                $query->with('khachThue')->orderBy('ngayBatDau', 'desc');
            },
        ])->findOrFail($maPhong);

        $danhSachLoaiPhong = LoaiPhong::where('dangHoatDong', 1)
            ->withCount(['phongTro' => function ($query) {
                $query->where('dangHoatDong', 1);
            }])
            ->orderBy('tenLoaiPhong', 'asc')
            ->get();
        $danhSachTienNghi = PhongTro::layDanhSachTienNghiHeThong();

        if ($yeuCau->wantsJson() || $yeuCau->ajax()) {
            return response()->json([
                'thanhCong' => true,
                'duLieu' => $phongTro,
                'danhSachTienNghi' => $danhSachTienNghi,
            ]);
        }

        return view('quan-ly-phong.chi-tiet', compact('phongTro', 'danhSachLoaiPhong', 'danhSachTienNghi'));
    }

    // Cập nhật thông tin phòng trọ đã có.
    public function capNhat(CapNhatPhongTroRequest $yeuCau, $maPhong)
    {
        $phongTro = PhongTro::findOrFail($maPhong);

        $phongTro->update([
            'soPhong' => trim($yeuCau->input('soPhong')),
            'maLoaiPhong' => $yeuCau->input('maLoaiPhong'),
            'tang' => $yeuCau->filled('tang') ? trim($yeuCau->input('tang')) : 'Tầng 1',
            'dienTich' => $yeuCau->filled('dienTich') ? $yeuCau->input('dienTich') : null,
            'tienCocMacDinh' => $yeuCau->filled('tienCocMacDinh') ? $yeuCau->input('tienCocMacDinh') : 0,
            'tienNghi' => $yeuCau->input('tienNghi', []),
            'soNguoiToiDa' => $yeuCau->input('soNguoiToiDa'),
            'donGiaMacDinh' => $yeuCau->input('donGiaMacDinh'),
            'ghiChu' => $yeuCau->input('ghiChu'),
        ]);

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => "Cập nhật phòng {$phongTro->soPhong} thành công.",
                'duLieu' => $phongTro->load('loaiPhong'),
            ]);
        }

        return redirect()->route('phong-tro.danh-sach')
            ->with('thongBaoThanhCong', "Cập nhật phòng {$phongTro->soPhong} thành công.");
    }

    // Chuyển đổi trạng thái vận hành giữa TRONG và DANG_SUA_CHUA.
    public function doiTrangThai(Request $yeuCau, $maPhong)
    {
        $phongTro = PhongTro::with('hopDongHieuLuc')->findOrFail($maPhong);

        if ($phongTro->trangThaiPhong === 'DANG_THUE' || $phongTro->hopDongHieuLuc !== null) {
            $thongDiepLoi = 'Phòng đang có hợp đồng thuê có hiệu lực, không thể chuyển đổi trạng thái thủ công.';

            if ($yeuCau->wantsJson()) {
                return response()->json([
                    'thanhCong' => false,
                    'thongBao' => $thongDiepLoi,
                ], 422);
            }

            return back()->with('thongBaoLoi', $thongDiepLoi);
        }

        $trangThaiMoi = ($phongTro->trangThaiPhong === 'TRONG') ? 'DANG_SUA_CHUA' : 'TRONG';
        $phongTro->update(['trangThaiPhong' => $trangThaiMoi]);

        $tenTrangThai = $trangThaiMoi === 'TRONG' ? 'Sẵn sàng đón khách (Trống)' : 'Đang sửa chữa';
        $thongDiepThanhCong = "Đã chuyển trạng thái phòng {$phongTro->soPhong} sang '{$tenTrangThai}'.";

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => $thongDiepThanhCong,
                'trangThaiMoi' => $trangThaiMoi,
            ]);
        }

        return back()->with('thongBaoThanhCong', $thongDiepThanhCong);
    }

    // Xóa mềm phòng trọ (cập nhật dangHoatDong = 0).
    public function xoaMem(Request $yeuCau, $maPhong)
    {
        $phongTro = PhongTro::with('hopDongHieuLuc')->findOrFail($maPhong);

        if ($phongTro->trangThaiPhong === 'DANG_THUE' || $phongTro->hopDongHieuLuc !== null) {
            $thongDiepLoi = 'Không thể xóa phòng đang có hợp đồng thuê có hiệu lực. Vui lòng kết thúc hợp đồng trước khi xóa.';

            if ($yeuCau->wantsJson()) {
                return response()->json([
                    'thanhCong' => false,
                    'thongBao' => $thongDiepLoi,
                ], 422);
            }

            return back()->with('thongBaoLoi', $thongDiepLoi);
        }

        $phongTro->update(['dangHoatDong' => 0]);

        $thongDiepThanhCong = "Đã ngưng hoạt động phòng {$phongTro->soPhong} thành công.";

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => $thongDiepThanhCong,
            ]);
        }

        return redirect()->route('phong-tro.danh-sach')
            ->with('thongBaoThanhCong', $thongDiepThanhCong);
    }

    // Khôi phục phòng trọ đã xóa mềm.
    public function khoiPhuc(Request $yeuCau, $maPhong)
    {
        $phongTro = PhongTro::findOrFail($maPhong);
        $phongTro->update(['dangHoatDong' => 1]);

        $thongDiep = "Đã mở lại hoạt động cho phòng {$phongTro->soPhong}.";

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => $thongDiep,
            ]);
        }

        return redirect()->route('phong-tro.danh-sach')
            ->with('thongBaoThanhCong', $thongDiep);
    }

    // Thêm mới loại phòng (thể loại phòng) ngay trong trang Quản lý phòng.
    public function taoLoaiPhong(Request $yeuCau)
    {
        if ($yeuCau->has('donGiaGoiY') && is_string($yeuCau->input('donGiaGoiY'))) {
            $yeuCau->merge([
                'donGiaGoiY' => str_replace(['.', ','], '', $yeuCau->input('donGiaGoiY')),
            ]);
        }

        $duLieu = $yeuCau->validate([
            'tenLoaiPhong' => ['required', 'string', 'max:100'],
            'dienTich' => ['nullable', 'numeric', 'min:1', 'max:500'],
            'donGiaGoiY' => ['required', 'numeric', 'min:0'],
            'moTa' => ['nullable', 'string', 'max:255'],
        ], [
            'tenLoaiPhong.required' => 'Vui lòng nhập tên loại phòng.',
            'donGiaGoiY.required' => 'Vui lòng nhập đơn giá gợi ý.',
        ]);

        $loaiPhong = LoaiPhong::create([
            'tenLoaiPhong' => trim($duLieu['tenLoaiPhong']),
            'soNguoiToiDa' => $yeuCau->input('soNguoiToiDa', 2),
            'dienTich' => $duLieu['dienTich'] ?? null,
            'donGiaGoiY' => $duLieu['donGiaGoiY'],
            'moTa' => $duLieu['moTa'] ?? null,
            'dangHoatDong' => 1,
        ]);

        return redirect()->route('phong-tro.danh-sach')
            ->with('thongBaoThanhCong', "Đã thêm loại phòng '{$loaiPhong->tenLoaiPhong}' thành công.");
    }

    // Cập nhật thông tin loại phòng.
    public function capNhatLoaiPhong(Request $yeuCau, $maLoaiPhong)
    {
        $loaiPhong = LoaiPhong::findOrFail($maLoaiPhong);

        if ($yeuCau->has('donGiaGoiY') && is_string($yeuCau->input('donGiaGoiY'))) {
            $yeuCau->merge([
                'donGiaGoiY' => str_replace(['.', ','], '', $yeuCau->input('donGiaGoiY')),
            ]);
        }

        $duLieu = $yeuCau->validate([
            'tenLoaiPhong' => ['required', 'string', 'max:100'],
            'dienTich' => ['nullable', 'numeric', 'min:1', 'max:500'],
            'donGiaGoiY' => ['required', 'numeric', 'min:0'],
            'moTa' => ['nullable', 'string', 'max:255'],
        ], [
            'tenLoaiPhong.required' => 'Vui lòng nhập tên loại phòng.',
            'donGiaGoiY.required' => 'Vui lòng nhập đơn giá gợi ý.',
        ]);

        $loaiPhong->update([
            'tenLoaiPhong' => trim($duLieu['tenLoaiPhong']),
            'dienTich' => $duLieu['dienTich'] ?? null,
            'donGiaGoiY' => $duLieu['donGiaGoiY'],
            'moTa' => $duLieu['moTa'] ?? null,
        ]);

        return redirect()->route('phong-tro.danh-sach')
            ->with('thongBaoThanhCong', "Đã cập nhật loại phòng '{$loaiPhong->tenLoaiPhong}' thành công.");
    }

    // Xóa mềm loại phòng nếu chưa có phòng đang hoạt động gắn vào.
    public function xoaLoaiPhong($maLoaiPhong)
    {
        $loaiPhong = LoaiPhong::findOrFail($maLoaiPhong);

        $soPhongDangDung = PhongTro::where('maLoaiPhong', $maLoaiPhong)
            ->where('dangHoatDong', 1)
            ->count();

        if ($soPhongDangDung > 0) {
            return back()->with('thongBaoLoi', "Không thể xóa loại phòng '{$loaiPhong->tenLoaiPhong}' vì đang có {$soPhongDangDung} phòng thuộc loại này.");
        }

        $loaiPhong->update(['dangHoatDong' => 0]);

        return redirect()->route('phong-tro.danh-sach')
            ->with('thongBaoThanhCong', "Đã xóa loại phòng '{$loaiPhong->tenLoaiPhong}'.");
    }
}
