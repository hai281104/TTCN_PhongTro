<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapNhatKhachThueRequest;
use App\Http\Requests\LuuKhachThueRequest;
use App\Models\HopDong;
use App\Models\KhachThue;
use App\Models\NguoiDung;
use App\Models\NguoiOCung;
use App\Models\PhongTro;
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
                    ->orWhere('bienSoXe', 'like', "%{$tuKhoa}%")
                    ->orWhere('ngheNghiep', 'like', "%{$tuKhoa}%");
            });
        }

        // Lọc theo tình trạng thuê/ở phòng
        if ($yeuCau->filled('tinhTrang')) {
            $tinhTrang = $yeuCau->input('tinhTrang');
            if ($tinhTrang === 'DANG_THUE' || $tinhTrang === 'DANG_O') {
                $truyVan->where(function ($q) {
                    $q->whereHas('hopDongHieuLuc')
                      ->orWhereHas('nguoiOCungHienTai.hopDong', function ($sub) {
                          $sub->where('trangThaiHD', 'HIEU_LUC');
                      });
                });
            } elseif ($tinhTrang === 'CHUA_THUE') {
                $truyVan->whereDoesntHave('hopDongHieuLuc')
                        ->whereDoesntHave('nguoiOCungHienTai.hopDong', function ($sub) {
                            $sub->where('trangThaiHD', 'HIEU_LUC');
                        });
            } elseif ($tinhTrang === 'DAI_DIEN') {
                $truyVan->whereHas('hopDongHieuLuc');
            } elseif ($tinhTrang === 'O_CUNG') {
                $truyVan->whereHas('nguoiOCungHienTai.hopDong', function ($sub) {
                    $sub->where('trangThaiHD', 'HIEU_LUC');
                });
            }
        }

        // Lọc theo phòng cụ thể
        if ($yeuCau->filled('maPhong')) {
            $maPhong = $yeuCau->input('maPhong');
            if ($maPhong === 'CHUA_CO') {
                $truyVan->whereDoesntHave('hopDongHieuLuc')
                        ->whereDoesntHave('nguoiOCungHienTai.hopDong', function ($sub) {
                            $sub->where('trangThaiHD', 'HIEU_LUC');
                        });
            } elseif (is_numeric($maPhong)) {
                $truyVan->where(function ($q) use ($maPhong) {
                    $q->whereHas('hopDongHieuLuc', function ($sub) use ($maPhong) {
                        $sub->where('maPhong', $maPhong);
                    })->orWhereHas('nguoiOCungHienTai.hopDong', function ($sub) use ($maPhong) {
                        $sub->where('maPhong', $maPhong)->where('trangThaiHD', 'HIEU_LUC');
                    });
                });
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

        $danhSachKhach = $truyVan->with([
            'nguoiDung',
            'hopDongHieuLuc.phongTro.loaiPhong',
            'nguoiOCungHienTai.hopDong.phongTro.loaiPhong',
            'nguoiOCungHienTai.hopDong.khachThue',
        ])
            ->orderBy('hoTen', 'asc')
            ->paginate(12)
            ->withQueryString();

        // Danh sách các hợp đồng đang có hiệu lực (đang cho thuê) để xếp phòng ở cùng
        $danhSachHopDongDangThue = HopDong::with(['phongTro.loaiPhong', 'khachThue', 'danhSachNguoiOCung'])
            ->where('trangThaiHD', 'HIEU_LUC')
            ->get();

        // Danh sách phòng trọ phục vụ bộ lọc
        $danhSachPhongTro = PhongTro::where('dangHoatDong', 1)->orderBy('soPhong', 'asc')->get();

        $tongSoKhach = KhachThue::where('dangHoatDong', 1)->count();
        $soKhachDangThue = KhachThue::where('dangHoatDong', 1)->where(function ($q) {
            $q->whereHas('hopDongHieuLuc')
              ->orWhereHas('nguoiOCungHienTai.hopDong', function ($sub) {
                  $sub->where('trangThaiHD', 'HIEU_LUC');
              });
        })->count();
        $soKhachChuaThue = $tongSoKhach - $soKhachDangThue;
        $soKhachCoTaiKhoan = KhachThue::where('dangHoatDong', 1)->whereNotNull('maNguoiDung')->count();
        $soKhachChuaTamTru = KhachThue::where('dangHoatDong', 1)->where('daDangKyTamTru', 0)->count();

        return view('quan-ly-khach-thue.danh-sach', compact(
            'danhSachKhach',
            'danhSachHopDongDangThue',
            'danhSachPhongTro',
            'tongSoKhach',
            'soKhachDangThue',
            'soKhachChuaThue',
            'soKhachCoTaiKhoan',
            'soKhachChuaTamTru'
        ));
    }

    // Lưu hồ sơ khách thuê mới (kèm xếp phòng ở cùng nếu chọn).
    public function taoMoi(LuuKhachThueRequest $yeuCau)
    {
        $maHopDongOCung = $yeuCau->input('maHopDongOCung');

        // Kiểm tra sức chứa phòng nếu có chọn gán vào phòng ở cùng
        if ($maHopDongOCung) {
            $hopDong = HopDong::with(['phongTro', 'danhSachNguoiOCung'])
                ->where('trangThaiHD', 'HIEU_LUC')
                ->findOrFail($maHopDongOCung);

            $soNguoiToiDa = $hopDong->phongTro ? $hopDong->phongTro->soNguoiToiDa : 1;
            $soNguoiHienTai = 1 + $hopDong->danhSachNguoiOCung->count();

            if ($soNguoiHienTai >= $soNguoiToiDa) {
                $thongBaoLoi = "Phòng {$hopDong->phongTro->soPhong} đã đạt sức chứa tối đa ({$soNguoiToiDa} người). Không thể thêm người ở cùng.";
                if ($yeuCau->wantsJson()) {
                    return response()->json(['thanhCong' => false, 'thongBao' => $thongBaoLoi], 422);
                }
                return back()->withInput()->with('thongBaoLoi', $thongBaoLoi);
            }
        }

        $khachMoi = DB::transaction(function () use ($yeuCau, $maHopDongOCung) {
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

            $khach = KhachThue::create([
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

            // Gán vào phòng ở cùng nếu có chọn
            if ($maHopDongOCung) {
                NguoiOCung::create([
                    'maHopDong' => $maHopDongOCung,
                    'maKhach' => $khach->maKhach,
                    'ngayVaoO' => now()->toDateString(),
                    'ghiChu' => $yeuCau->filled('quanHeVoiDaiDien') ? trim($yeuCau->input('quanHeVoiDaiDien')) : 'Ở cùng',
                    'dangHoatDong' => 1,
                ]);

                // Tăng số lượng người ở trong hợp đồng
                HopDong::where('maHopDong', $maHopDongOCung)->increment('soLuongNguoi');
            }

            return $khach;
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
            'hopDongHieuLuc.phongTro.loaiPhong',
            'hopDongHieuLuc.danhSachNguoiOCung.khachThue',
            'nguoiOCungHienTai.hopDong.phongTro.loaiPhong',
            'nguoiOCungHienTai.hopDong.khachThue',
            'nguoiOCungHienTai.hopDong.danhSachNguoiOCung.khachThue',
            'hopDong' => function ($q) {
                $q->with(['phongTro.loaiPhong', 'hoaDon'])->orderBy('ngayBatDau', 'desc');
            },
        ])->findOrFail($maKhach);

        $thongTinPhong = $khachThue->layThongTinPhongHienTai();

        if ($yeuCau->wantsJson() || $yeuCau->ajax()) {
            return response()->json([
                'thanhCong' => true,
                'duLieu' => $khachThue,
                'thongTinPhong' => $thongTinPhong,
            ]);
        }

        $danhSachHopDongDangThue = HopDong::with(['phongTro.loaiPhong', 'khachThue', 'danhSachNguoiOCung'])
            ->where('trangThaiHD', 'HIEU_LUC')
            ->get();

        return view('quan-ly-khach-thue.chi-tiet', compact('khachThue', 'thongTinPhong', 'danhSachHopDongDangThue'));
    }

    // Cập nhật thông tin nhân thân khách thuê và gán / đổi / rời phòng ở cùng.
    public function capNhat(CapNhatKhachThueRequest $yeuCau, $maKhach)
    {
        $khachThue = KhachThue::with(['hopDongHieuLuc', 'nguoiOCungHienTai'])->findOrFail($maKhach);
        $maHopDongMoi = $yeuCau->input('maHopDongOCung');

        // Nếu khách đang là đại diện ký hợp đồng thì không thể chuyển thành người ở cùng
        if ($khachThue->hopDongHieuLuc && $maHopDongMoi && $khachThue->hopDongHieuLuc->maHopDong != $maHopDongMoi) {
            $thongBaoLoi = "Khách thuê {$khachThue->hoTen} hiện đang là người đại diện ký hợp đồng phòng {$khachThue->hopDongHieuLuc->phongTro->soPhong}. Không thể gán làm người ở cùng phòng khác.";
            if ($yeuCau->wantsJson()) {
                return response()->json(['thanhCong' => false, 'thongBao' => $thongBaoLoi], 422);
            }
            return back()->withInput()->with('thongBaoLoi', $thongBaoLoi);
        }

        DB::transaction(function () use ($yeuCau, $khachThue, $maHopDongMoi) {
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

            // Xử lý thay đổi phòng ở cùng nếu không phải người đại diện
            if (!$khachThue->hopDongHieuLuc) {
                $lienKetCu = NguoiOCung::where('maKhach', $khachThue->maKhach)->where('dangHoatDong', 1)->first();

                if ($maHopDongMoi && is_numeric($maHopDongMoi)) {
                    if ($lienKetCu && $lienKetCu->maHopDong == $maHopDongMoi) {
                        // Cùng phòng, chỉ cập nhật ghi chú
                        $lienKetCu->update([
                            'ghiChu' => $yeuCau->filled('quanHeVoiDaiDien') ? trim($yeuCau->input('quanHeVoiDaiDien')) : $lienKetCu->ghiChu,
                        ]);
                    } else {
                        // Chuyển sang phòng khác
                        $hopDongMoi = HopDong::with(['phongTro', 'danhSachNguoiOCung'])
                            ->where('trangThaiHD', 'HIEU_LUC')
                            ->findOrFail($maHopDongMoi);

                        $soNguoiToiDa = $hopDongMoi->phongTro ? $hopDongMoi->phongTro->soNguoiToiDa : 1;
                        $soNguoiHienTai = 1 + $hopDongMoi->danhSachNguoiOCung->count();

                        if ($soNguoiHienTai >= $soNguoiToiDa) {
                            throw new \Exception("Phòng {$hopDongMoi->phongTro->soPhong} đã đủ sức chứa tối đa ({$soNguoiToiDa} người).");
                        }

                        if ($lienKetCu) {
                            $lienKetCu->update(['dangHoatDong' => 0]);
                            HopDong::where('maHopDong', $lienKetCu->maHopDong)->decrement('soLuongNguoi');
                        }

                        NguoiOCung::create([
                            'maHopDong' => $maHopDongMoi,
                            'maKhach' => $khachThue->maKhach,
                            'ngayVaoO' => now()->toDateString(),
                            'ghiChu' => $yeuCau->filled('quanHeVoiDaiDien') ? trim($yeuCau->input('quanHeVoiDaiDien')) : 'Ở cùng',
                            'dangHoatDong' => 1,
                        ]);

                        $hopDongMoi->increment('soLuongNguoi');
                    }
                } else {
                    // Rời khỏi phòng (Chưa gán phòng)
                    if ($lienKetCu) {
                        $lienKetCu->update(['dangHoatDong' => 0]);
                        HopDong::where('maHopDong', $lienKetCu->maHopDong)->decrement('soLuongNguoi');
                    }
                }
            }
        });

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
        $khachThue = KhachThue::with(['hopDongHieuLuc', 'nguoiOCungHienTai'])->findOrFail($maKhach);

        if ($khachThue->hopDongHieuLuc !== null) {
            $thongBaoLoi = 'Không thể xóa khách đang là người đại diện ký hợp đồng thuê có hiệu lực. Vui lòng thanh lý hoặc chuyển người đại diện trước khi xóa.';
            if ($yeuCau->wantsJson()) {
                return response()->json(['thanhCong' => false, 'thongBao' => $thongBaoLoi], 422);
            }
            return back()->with('thongBaoLoi', $thongBaoLoi);
        }

        DB::transaction(function () use ($khachThue) {
            // Nếu đang là người ở cùng thì rút khỏi hợp đồng
            if ($khachThue->nguoiOCungHienTai) {
                $maHopDong = $khachThue->nguoiOCungHienTai->maHopDong;
                $khachThue->nguoiOCungHienTai->update(['dangHoatDong' => 0]);
                HopDong::where('maHopDong', $maHopDong)->decrement('soLuongNguoi');
            }

            $khachThue->update(['dangHoatDong' => 0]);
        });

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
