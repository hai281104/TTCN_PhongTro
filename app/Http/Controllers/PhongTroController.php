<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapNhatPhongTroRequest;
use App\Http\Requests\LuuPhongTroRequest;
use App\Models\PhongTro;
use Illuminate\Http\Request;

class PhongTroController extends Controller
{
    /**
     * Hiển thị danh sách phòng trọ kèm bộ lọc và các thẻ thống kê.
     *
     * @param  \Illuminate\Http\Request  $yeuCau
     * @return \Illuminate\View\View
     */
    public function danhSach(Request $yeuCau)
    {
        $truyVan = PhongTro::query()->where('dangHoatDong', 1);

        if ($yeuCau->filled('tuKhoa')) {
            $tuKhoa = trim($yeuCau->input('tuKhoa'));
            $truyVan->where('soPhong', 'like', "%{$tuKhoa}%");
        }

        if ($yeuCau->filled('trangThaiPhong') && in_array($yeuCau->input('trangThaiPhong'), ['TRONG', 'DANG_THUE', 'DANG_SUA_CHUA'])) {
            $truyVan->where('trangThaiPhong', $yeuCau->input('trangThaiPhong'));
        }

        if ($yeuCau->filled('giaTu')) {
            $truyVan->where('donGiaMacDinh', '>=', $yeuCau->input('giaTu'));
        }

        if ($yeuCau->filled('giaDen')) {
            $truyVan->where('donGiaMacDinh', '<=', $yeuCau->input('giaDen'));
        }

        $danhSachPhong = $truyVan->with(['hopDongHieuLuc.khachThue', 'hopDongHieuLuc.nguoiOCung'])
            ->orderBy('soPhong', 'asc')
            ->paginate(12)
            ->withQueryString();

        $tongSoPhong = PhongTro::where('dangHoatDong', 1)->count();
        $soPhongDangThue = PhongTro::where('dangHoatDong', 1)->where('trangThaiPhong', 'DANG_THUE')->count();
        $soPhongTrong = PhongTro::where('dangHoatDong', 1)->where('trangThaiPhong', 'TRONG')->count();
        $soPhongDangSuaChua = PhongTro::where('dangHoatDong', 1)->where('trangThaiPhong', 'DANG_SUA_CHUA')->count();
        $tyLeLapDay = $tongSoPhong > 0 ? round(($soPhongDangThue / $tongSoPhong) * 100, 1) : 0;

        return view('quan-ly-phong.danh-sach', compact(
            'danhSachPhong',
            'tongSoPhong',
            'soPhongDangThue',
            'soPhongTrong',
            'soPhongDangSuaChua',
            'tyLeLapDay'
        ));
    }

    /**
     * Lưu thông tin phòng trọ mới vào hệ thống.
     *
     * @param  \App\Http\Requests\LuuPhongTroRequest  $yeuCau
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function taoMoi(LuuPhongTroRequest $yeuCau)
    {
        $phongMoi = PhongTro::create([
            'soPhong' => trim($yeuCau->input('soPhong')),
            'donGiaMacDinh' => $yeuCau->input('donGiaMacDinh'),
            'trangThaiPhong' => 'TRONG',
            'ghiChu' => $yeuCau->input('ghiChu'),
            'dangHoatDong' => 1,
        ]);

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => 'Thêm phòng trọ mới thành công.',
                'duLieu' => $phongMoi,
            ], 201);
        }

        return redirect()->route('phong-tro.danh-sach')
            ->with('thongBaoThanhCong', "Thêm phòng {$phongMoi->soPhong} thành công.");
    }

    /**
     * Lấy dữ liệu chi tiết của một phòng trọ.
     *
     * @param  \Illuminate\Http\Request  $yeuCau
     * @param  int  $maPhong
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function chiTiet(Request $yeuCau, $maPhong)
    {
        $phongTro = PhongTro::with([
            'hopDongHieuLuc.khachThue',
            'hopDongHieuLuc.nguoiOCung',
            'chiSoDienNuoc' => function ($query) {
                $query->orderBy('thangGhiNhan', 'desc')->take(3);
            },
            'hopDong' => function ($query) {
                $query->with('khachThue')->orderBy('ngayBatDau', 'desc');
            },
        ])->findOrFail($maPhong);

        if ($yeuCau->wantsJson() || $yeuCau->ajax()) {
            return response()->json([
                'thanhCong' => true,
                'duLieu' => $phongTro,
            ]);
        }

        return view('quan-ly-phong.chi-tiet', compact('phongTro'));
    }

    /**
     * Cập nhật thông tin phòng trọ đã có.
     *
     * @param  \App\Http\Requests\CapNhatPhongTroRequest  $yeuCau
     * @param  int  $maPhong
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function capNhat(CapNhatPhongTroRequest $yeuCau, $maPhong)
    {
        $phongTro = PhongTro::findOrFail($maPhong);

        $phongTro->update([
            'soPhong' => trim($yeuCau->input('soPhong')),
            'donGiaMacDinh' => $yeuCau->input('donGiaMacDinh'),
            'ghiChu' => $yeuCau->input('ghiChu'),
        ]);

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => "Cập nhật phòng {$phongTro->soPhong} thành công.",
                'duLieu' => $phongTro,
            ]);
        }

        return redirect()->route('phong-tro.danh-sach')
            ->with('thongBaoThanhCong', "Cập nhật phòng {$phongTro->soPhong} thành công.");
    }

    /**
     * Chuyển đổi trạng thái vận hành giữa TRONG và DANG_SUA_CHUA.
     *
     * @param  \Illuminate\Http\Request  $yeuCau
     * @param  int  $maPhong
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
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

    /**
     * Xóa mềm phòng trọ (cập nhật dangHoatDong = 0).
     *
     * @param  \Illuminate\Http\Request  $yeuCau
     * @param  int  $maPhong
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
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

    /**
     * Khôi phục phòng trọ đã ngưng hoạt động.
     *
     * @param  \Illuminate\Http\Request  $yeuCau
     * @param  int  $maPhong
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function khoiPhuc(Request $yeuCau, $maPhong)
    {
        $phongTro = PhongTro::findOrFail($maPhong);
        $phongTro->update(['dangHoatDong' => 1]);

        $thongDiepThanhCong = "Đã khôi phục hoạt động cho phòng {$phongTro->soPhong}.";

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => $thongDiepThanhCong,
            ]);
        }

        return redirect()->route('phong-tro.danh-sach')
            ->with('thongBaoThanhCong', $thongDiepThanhCong);
    }
}
