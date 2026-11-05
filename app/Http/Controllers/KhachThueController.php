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
    // Hiển thị danh sách hồ sơ khách thuê kèm bộ lọc và thẻ KPI thống kê.
    public function danhSach(Request $yeuCau)
    {
        $truyVan = KhachThue::query()->where('dangHoatDong', 1);

        if ($yeuCau->filled('tuKhoa')) {
            $tuKhoa = trim($yeuCau->input('tuKhoa'));
            $truyVan->where(function ($sub) use ($tuKhoa) {
                $sub->where('hoTen', 'like', "%{$tuKhoa}%")
                    ->orWhere('soCCCD', 'like', "%{$tuKhoa}%")
                    ->orWhere('soDienThoai', 'like', "%{$tuKhoa}%")
                    ->orWhere('bienSoXe', 'like', "%{$tuKhoa}%");
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

        if ($yeuCau->filled('tamTru')) {
            $tamTru = $yeuCau->input('tamTru');
            if ($tamTru === 'DA_DANG_KY') {
                $truyVan->where('daDangKyTamTru', 1);
            } elseif ($tamTru === 'CHUA_DANG_KY') {
                $truyVan->where('daDangKyTamTru', 0);
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

        $danhSachKhach = $truyVan->with(['nguoiDung', 'hopDongHieuLuc.phongTro.loaiPhong'])
            ->orderBy('hoTen', 'asc')
            ->paginate(12)
            ->withQueryString();

        $tongSoKhach = KhachThue::where('dangHoatDong', 1)->count();
        $soKhachDangThue = KhachThue::where('dangHoatDong', 1)->whereHas('hopDongHieuLuc')->count();
        $soKhachChuaThue = KhachThue::where('dangHoatDong', 1)->whereDoesntHave('hopDongHieuLuc')->count();
        $soKhachCoTaiKhoan = KhachThue::where('dangHoatDong', 1)->whereNotNull('maNguoiDung')->count();
        $soKhachChuaTamTru = KhachThue::where('dangHoatDong', 1)->where('daDangKyTamTru', 0)->count();

        return view('quan-ly-khach-thue.danh-sach', compact(
            'danhSachKhach',
            'tongSoKhach',
            'soKhachDangThue',
            'soKhachChuaThue',
            'soKhachCoTaiKhoan',
            'soKhachChuaTamTru'
        ));
    }

    // Lưu hồ sơ khách thuê mới (có thể kèm tạo tài khoản đăng nhập).
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
                'ngaySinh' => $yeuCau->input('ngaySinh'),
                'gioiTinh' => $yeuCau->input('gioiTinh', 'NAM'),
                'soCCCD' => trim($yeuCau->input('soCCCD')),
                'ngayCapCCCD' => $yeuCau->input('ngayCapCCCD'),
                'noiCapCCCD' => $yeuCau->filled('noiCapCCCD') ? trim($yeuCau->input('noiCapCCCD')) : 'Cục CSQLHC về TTXH',
                'soDienThoai' => trim($yeuCau->input('soDienThoai')),
                'diaChi' => $yeuCau->filled('diaChi') ? trim($yeuCau->input('diaChi')) : null,
                'ngheNghiep' => $yeuCau->filled('ngheNghiep') ? trim($yeuCau->input('ngheNghiep')) : null,
                'bienSoXe' => $yeuCau->filled('bienSoXe') ? trim($yeuCau->input('bienSoXe')) : null,
                'nguoiLienHeKhanCap' => $yeuCau->filled('nguoiLienHeKhanCap') ? trim($yeuCau->input('nguoiLienHeKhanCap')) : null,
                'sdtKhanCap' => $yeuCau->filled('sdtKhanCap') ? trim($yeuCau->input('sdtKhanCap')) : null,
                'quanHeKhanCap' => $yeuCau->filled('quanHeKhanCap') ? trim($yeuCau->input('quanHeKhanCap')) : null,
                'daDangKyTamTru' => $yeuCau->boolean('daDangKyTamTru'),
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

    // Xem thông tin chi tiết một hồ sơ khách thuê.
    public function chiTiet(Request $yeuCau, $maKhach)
    {
        $khachThue = KhachThue::with([
            'nguoiDung',
            'hopDongHieuLuc.phongTro',
            'hopDongHieuLuc.phongTro.loaiPhong',
            'hopDong' => function ($q) {
                $q->with(['phongTro.loaiPhong', 'hoaDon'])->orderBy('ngayBatDau', 'desc');
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

    // Cập nhật thông tin nhân thân khách thuê.
    public function capNhat(CapNhatKhachThueRequest $yeuCau, $maKhach)
    {
        $khachThue = KhachThue::findOrFail($maKhach);

        $khachThue->update([
            'hoTen' => trim($yeuCau->input('hoTen')),
            'ngaySinh' => $yeuCau->input('ngaySinh'),
            'gioiTinh' => $yeuCau->input('gioiTinh', 'NAM'),
            'soCCCD' => trim($yeuCau->input('soCCCD')),
            'ngayCapCCCD' => $yeuCau->input('ngayCapCCCD'),
            'noiCapCCCD' => $yeuCau->filled('noiCapCCCD') ? trim($yeuCau->input('noiCapCCCD')) : 'Cục CSQLHC về TTXH',
            'soDienThoai' => trim($yeuCau->input('soDienThoai')),
            'diaChi' => $yeuCau->filled('diaChi') ? trim($yeuCau->input('diaChi')) : null,
            'ngheNghiep' => $yeuCau->filled('ngheNghiep') ? trim($yeuCau->input('ngheNghiep')) : null,
            'bienSoXe' => $yeuCau->filled('bienSoXe') ? trim($yeuCau->input('bienSoXe')) : null,
            'nguoiLienHeKhanCap' => $yeuCau->filled('nguoiLienHeKhanCap') ? trim($yeuCau->input('nguoiLienHeKhanCap')) : null,
            'sdtKhanCap' => $yeuCau->filled('sdtKhanCap') ? trim($yeuCau->input('sdtKhanCap')) : null,
            'quanHeKhanCap' => $yeuCau->filled('quanHeKhanCap') ? trim($yeuCau->input('quanHeKhanCap')) : null,
            'daDangKyTamTru' => $yeuCau->boolean('daDangKyTamTru'),
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

    // Chuyển đổi nhanh trạng thái đăng ký tạm trú của khách thuê.
    public function doiTrangThaiTamTru(Request $yeuCau, $maKhach)
    {
        $khachThue = KhachThue::findOrFail($maKhach);
        $trangThaiMoi = !$khachThue->daDangKyTamTru;
        $khachThue->update(['daDangKyTamTru' => $trangThaiMoi]);

        $tenTrangThai = $trangThaiMoi ? 'Đã đăng ký tạm trú' : 'Chưa đăng ký tạm trú';
        $thongDiep = "Đã cập nhật tình trạng tạm trú của {$khachThue->hoTen}: {$tenTrangThai}.";

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => $thongDiep,
                'daDangKyTamTru' => $trangThaiMoi,
            ]);
        }

        return back()->with('thongBaoThanhCong', $thongDiep);
    }

    // Cấp tài khoản đăng nhập cho khách thuê chưa có tài khoản.
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

    // Xóa mềm hồ sơ khách thuê (cập nhật dangHoatDong = 0).
    public function xoaMem(Request $yeuCau, $maKhach)
    {
        $khachThue = KhachThue::with('hopDongHieuLuc')->findOrFail($maKhach);

        if ($khachThue->hopDongHieuLuc !== null) {
            $thongBaoLoi = 'Không thể xóa khách đang có hợp đồng thuê có hiệu lực. Vui lòng thanh lý hợp đồng trước khi xóa.';
            if ($yeuCau->wantsJson()) {
                return response()->json(['thanhCong' => false, 'thongBao' => $thongBaoLoi], 422);
            }
            return back()->with('thongBaoLoi', $thongBaoLoi);
        }

        $khachThue->update(['dangHoatDong' => 0]);

        $thongDiep = "Đã xóa hồ sơ khách thuê {$khachThue->hoTen} thành công.";

        if ($yeuCau->wantsJson()) {
            return response()->json([
                'thanhCong' => true,
                'thongBao' => $thongDiep,
            ]);
        }

        return redirect()->route('khach-thue.danh-sach')
            ->with('thongBaoThanhCong', $thongDiep);
    }

    // Khôi phục hồ sơ khách thuê đã xóa mềm.
    public function khoiPhuc(Request $yeuCau, $maKhach)
    {
        $khachThue = KhachThue::findOrFail($maKhach);
        $khachThue->update(['dangHoatDong' => 1]);

        $thongDiep = "Đã khôi phục hồ sơ khách thuê {$khachThue->hoTen}.";

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
