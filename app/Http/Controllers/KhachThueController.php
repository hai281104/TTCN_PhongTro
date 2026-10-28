<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapNhatKhachThueRequest;
use App\Http\Requests\LuuKhachThueRequest;
use App\Models\KhachThue;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KhachThueController extends Controller
{
    /**
     * Hiển thị danh sách hồ sơ khách thuê kèm bộ lọc và thẻ KPI thống kê.
     *
     * @param  \Illuminate\Http\Request  $yeuCau
     * @return \Illuminate\View\View
     */
    public function danhSach(Request $yeuCau)
    {
        $truyVan = KhachThue::query()->where('dangHoatDong', 1);

        if ($yeuCau->filled('tuKhoa')) {
            $tuKhoa = trim($yeuCau->input('tuKhoa'));
            $truyVan->where(function ($sub) use ($tuKhoa) {
                $sub->where('hoTen', 'like', "%{$tuKhoa}%")
                    ->orWhere('soCCCD', 'like', "%{$tuKhoa}%")
                    ->orWhere('soDienThoai', 'like', "%{$tuKhoa}%");
            });
        }

        if ($yeuCau->filled('tinhTrang')) {
            $tinhTrang = $yeuCau->input('tinhTrang');
            if ($tinhTrang === 'DANG_THUE') {
                $truyVan->whereHas('hopDongHieuLuc');
            } elseif ($tinhTrang === 'CHUA_THUE') {
                $truyVan->whereDoesntHave('hopDongHieuLuc');
            }
        }

        if ($yeuCau->filled('taiKhoan')) {
            $taiKhoan = $yeuCau->input('taiKhoan');
            if ($taiKhoan === 'CO_TAI_KHOAN') {
                $truyVan->whereNotNull('maNguoiDung');
            } elseif ($taiKhoan === 'CHUA_CO_TAI_KHOAN') {
                $truyVan->whereNull('maNguoiDung');
            }
        }

        $danhSachKhach = $truyVan->with(['nguoiDung', 'hopDongHieuLuc.phongTro', 'hopDongHieuLuc.nguoiOCung'])
            ->orderBy('hoTen', 'asc')
            ->paginate(12)
            ->withQueryString();

        $tongSoKhach = KhachThue::where('dangHoatDong', 1)->count();
        $soKhachDangThue = KhachThue::where('dangHoatDong', 1)->whereHas('hopDongHieuLuc')->count();
        $soKhachChuaThue = KhachThue::where('dangHoatDong', 1)->whereDoesntHave('hopDongHieuLuc')->count();
        $soKhachCoTaiKhoan = KhachThue::where('dangHoatDong', 1)->whereNotNull('maNguoiDung')->count();

        return view('quan-ly-khach-thue.danh-sach', compact(
            'danhSachKhach',
            'tongSoKhach',
            'soKhachDangThue',
            'soKhachChuaThue',
            'soKhachCoTaiKhoan'
        ));
    }

    /**
     * Lưu hồ sơ khách thuê mới (có thể kèm tạo tài khoản đăng nhập).
     *
     * @param  \App\Http\Requests\LuuKhachThueRequest  $yeuCau
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function taoMoi(LuuKhachThueRequest $yeuCau)
    {
        $khachMoi = DB::transaction(function () use ($yeuCau) {
            $maNguoiDung = null;

            if ($yeuCau->boolean('taoTaiKhoan')) {
                $nguoiDung = NguoiDung::create([
                    'email' => strtolower(trim($yeuCau->input('email'))),
                    'matKhau' => Hash::make($yeuCau->input('matKhau')),
                    'vaiTro' => 'KHACH_THUE',
                    'dangHoatDong' => 1,
                ]);
                $maNguoiDung = $nguoiDung->maNguoiDung;
            }

            return KhachThue::create([
                'maNguoiDung' => $maNguoiDung,
                'hoTen' => trim($yeuCau->input('hoTen')),
                'soCCCD' => trim($yeuCau->input('soCCCD')),
                'soDienThoai' => trim($yeuCau->input('soDienThoai')),
                'diaChi' => $yeuCau->input('diaChi') ? trim($yeuCau->input('diaChi')) : null,
                'dangHoatDong' => 1,
            ]);
        });

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => "Thêm hồ sơ khách thuê {$khachMoi->hoTen} thành công.",
                'duLieu' => $khachMoi,
            ], 201);
        }

        return redirect()->route('khach-thue.danh-sach')
            ->with('thongBaoThanhCong', "Thêm hồ sơ khách thuê {$khachMoi->hoTen} thành công.");
    }

    /**
     * Xem thông tin chi tiết một hồ sơ khách thuê.
     *
     * @param  \Illuminate\Http\Request  $yeuCau
     * @param  int  $maKhach
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function chiTiet(Request $yeuCau, $maKhach)
    {
        $khachThue = KhachThue::with([
            'nguoiDung',
            'hopDongHieuLuc.phongTro',
            'hopDongHieuLuc.nguoiOCung',
            'hopDong' => function ($q) {
                $q->with(['phongTro', 'hoaDon'])->orderBy('ngayBatDau', 'desc');
            },
        ])->findOrFail($maKhach);

        if ($yeuCau->wantsJson() || $yeuCau->ajax()) {
            return response()->json([
                'thanhCong' => true,
                'duLieu' => $khachThue,
            ]);
        }

        return view('quan-ly-khach-thue.chi-tiet', compact('khachThue'));
    }

    /**
     * Cập nhật thông tin nhân thân khách thuê.
     *
     * @param  \App\Http\Requests\CapNhatKhachThueRequest  $yeuCau
     * @param  int  $maKhach
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function capNhat(CapNhatKhachThueRequest $yeuCau, $maKhach)
    {
        $khachThue = KhachThue::findOrFail($maKhach);

        $khachThue->update([
            'hoTen' => trim($yeuCau->input('hoTen')),
            'soCCCD' => trim($yeuCau->input('soCCCD')),
            'soDienThoai' => trim($yeuCau->input('soDienThoai')),
            'diaChi' => $yeuCau->input('diaChi') ? trim($yeuCau->input('diaChi')) : null,
        ]);

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => "Cập nhật hồ sơ khách {$khachThue->hoTen} thành công.",
                'duLieu' => $khachThue,
            ]);
        }

        return redirect()->route('khach-thue.danh-sach')
            ->with('thongBaoThanhCong', "Cập nhật hồ sơ khách {$khachThue->hoTen} thành công.");
    }

    /**
     * Cấp tài khoản đăng nhập cho khách thuê chưa có tài khoản.
     *
     * @param  \Illuminate\Http\Request  $yeuCau
     * @param  int  $maKhach
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function capTaiKhoan(Request $yeuCau, $maKhach)
    {
        $khachThue = KhachThue::findOrFail($maKhach);

        if ($khachThue->maNguoiDung) {
            $thongBaoLoi = 'Khách thuê này đã có tài khoản đăng nhập liên kết.';
            if ($yeuCau->wantsJson()) {
                return response()->json(['thanhCong' => false, 'thongBao' => $thongBaoLoi], 422);
            }
            return back()->with('thongBaoLoi', $thongBaoLoi);
        }

        $yeuCau->validate([
            'email' => ['required', 'email', 'max:150', 'unique:NguoiDung,email'],
            'matKhau' => ['required', 'string', 'min:6'],
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.unique' => 'Địa chỉ email này đã được sử dụng.',
            'matKhau.required' => 'Vui lòng nhập mật khẩu.',
            'matKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        DB::transaction(function () use ($yeuCau, $khachThue) {
            $nguoiDung = NguoiDung::create([
                'email' => strtolower(trim($yeuCau->input('email'))),
                'matKhau' => Hash::make($yeuCau->input('matKhau')),
                'vaiTro' => 'KHACH_THUE',
                'dangHoatDong' => 1,
            ]);

            $khachThue->update(['maNguoiDung' => $nguoiDung->maNguoiDung]);
        });

        $thongDiep = "Đã cấp tài khoản đăng nhập cho khách {$khachThue->hoTen} thành công.";

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => $thongDiep,
            ]);
        }

        return redirect()->route('khach-thue.danh-sach')
            ->with('thongBaoThanhCong', $thongDiep);
    }

    /**
     * Xóa mềm hồ sơ khách thuê (cập nhật dangHoatDong = 0).
     *
     * @param  \Illuminate\Http\Request  $yeuCau
     * @param  int  $maKhach
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function xoaMem(Request $yeuCau, $maKhach)
    {
        $khachThue = KhachThue::with(['hopDongHieuLuc', 'hopDong.hoaDon'])->findOrFail($maKhach);

        if ($khachThue->hopDongHieuLuc) {
            $thongDiepLoi = 'Không thể xóa hồ sơ khách đang có hợp đồng thuê có hiệu lực. Vui lòng thanh lý hợp đồng trước khi xóa.';

            if ($yeuCau->wantsJson()) {
                return response()->json(['thanhCong' => false, 'thongBao' => $thongDiepLoi], 422);
            }

            return back()->with('thongBaoLoi', $thongDiepLoi);
        }

        $coHoaDonNo = false;
        foreach ($khachThue->hopDong as $hd) {
            foreach ($hd->hoaDon as $hoaDon) {
                if ($hoaDon->trangThaiThanhToan === 'CON_NO') {
                    $coHoaDonNo = true;
                    break 2;
                }
            }
        }

        if ($coHoaDonNo) {
            $thongDiepLoi = 'Không thể xóa hồ sơ khách đang còn nợ hóa đơn tiền phòng/dịch vụ. Vui lòng thu hồi công nợ trước.';

            if ($yeuCau->wantsJson()) {
                return response()->json(['thanhCong' => false, 'thongBao' => $thongDiepLoi], 422);
            }

            return back()->with('thongBaoLoi', $thongDiepLoi);
        }

        DB::transaction(function () use ($khachThue) {
            $khachThue->update(['dangHoatDong' => 0]);

            if ($khachThue->maNguoiDung) {
                NguoiDung::where('maNguoiDung', $khachThue->maNguoiDung)
                    ->update(['dangHoatDong' => 0]);
            }
        });

        $thongDiep = "Đã ngưng hoạt động hồ sơ khách {$khachThue->hoTen} thành công.";

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => $thongDiep,
            ]);
        }

        return redirect()->route('khach-thue.danh-sach')
            ->with('thongBaoThanhCong', $thongDiep);
    }

    /**
     * Khôi phục hồ sơ khách thuê đã ngưng hoạt động.
     *
     * @param  \Illuminate\Http\Request  $yeuCau
     * @param  int  $maKhach
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function khoiPhuc(Request $yeuCau, $maKhach)
    {
        $khachThue = KhachThue::findOrFail($maKhach);

        DB::transaction(function () use ($khachThue) {
            $khachThue->update(['dangHoatDong' => 1]);

            if ($khachThue->maNguoiDung) {
                NguoiDung::where('maNguoiDung', $khachThue->maNguoiDung)
                    ->update(['dangHoatDong' => 1]);
            }
        });

        $thongDiep = "Đã khôi phục hoạt động cho hồ sơ khách {$khachThue->hoTen}.";

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => $thongDiep,
            ]);
        }

        return redirect()->route('khach-thue.danh-sach')
            ->with('thongBaoThanhCong', $thongDiep);
    }
}

