<?php

namespace App\Http\Controllers;

use App\Http\Requests\KetThucHopDongRequest;
use App\Http\Requests\LuuHopDongRequest;
use App\Http\Requests\ThemNguoiOCungRequest;
use App\Models\HopDong;
use App\Models\KhachThue;
use App\Models\NguoiOCung;
use App\Models\PhongTro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HopDongController extends Controller
{
    // Hiển thị danh sách các hợp đồng thuê phòng kèm bộ lọc và các thẻ thống kê.
    public function danhSach(Request $yeuCau)
    {
        $truyVan = HopDong::with(['phongTro', 'khachThue', 'nguoiOCung']);

        // Tìm kiếm theo từ khóa: số phòng, họ tên khách, CCCD hoặc số điện thoại
        if ($yeuCau->filled('tuKhoa')) {
            $tuKhoa = trim($yeuCau->input('tuKhoa'));
            $truyVan->where(function ($sub) use ($tuKhoa) {
                $sub->whereHas('phongTro', function ($qPhong) use ($tuKhoa) {
                    $qPhong->where('soPhong', 'like', "%{$tuKhoa}%");
                })->orWhereHas('khachThue', function ($qKhach) use ($tuKhoa) {
                    $qKhach->where('hoTen', 'like', "%{$tuKhoa}%")
                        ->orWhere('soCCCD', 'like', "%{$tuKhoa}%")
                        ->orWhere('soDienThoai', 'like', "%{$tuKhoa}%");
                });
            });
        }

        // Lọc theo trạng thái hợp đồng (HIEU_LUC hoặc DA_KET_THUC)
        if ($yeuCau->filled('trangThaiHD') && in_array($yeuCau->input('trangThaiHD'), ['HIEU_LUC', 'DA_KET_THUC'])) {
            $truyVan->where('trangThaiHD', $yeuCau->input('trangThaiHD'));
        }

        // Sắp xếp mặc định: hợp đồng đang hiệu lực lên trước, mã giảm dần
        $danhSachHopDong = $truyVan->orderByRaw("FIELD(trangThaiHD, 'HIEU_LUC', 'DA_KET_THUC')")
            ->orderBy('maHopDong', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Thống kê số liệu KPI
        $tongHopDong = HopDong::count();
        $soHopDongHieuLuc = HopDong::where('trangThaiHD', 'HIEU_LUC')->count();
        $soHopDongDaKetThuc = HopDong::where('trangThaiHD', 'DA_KET_THUC')->count();
        $tongTienCocDangGiu = HopDong::where('trangThaiHD', 'HIEU_LUC')->sum('tienDatCoc');

        // Lấy danh sách phòng trống và khách thuê đang hoạt động để phục vụ modal tạo hợp đồng
        $danhSachPhongTrong = PhongTro::where('dangHoatDong', 1)
            ->where('trangThaiPhong', 'TRONG')
            ->orderBy('soPhong', 'asc')
            ->get(['maPhong', 'soPhong', 'donGiaMacDinh']);

        $danhSachKhachThue = KhachThue::where('dangHoatDong', 1)
            ->orderBy('hoTen', 'asc')
            ->get(['maKhach', 'hoTen', 'soCCCD', 'soDienThoai']);

        return view('quan-ly-hop-dong.danh-sach', compact(
            'danhSachHopDong',
            'tongHopDong',
            'soHopDongHieuLuc',
            'soHopDongDaKetThuc',
            'tongTienCocDangGiu',
            'danhSachPhongTrong',
            'danhSachKhachThue'
        ));
    }

    // Tạo và lưu hợp đồng thuê phòng mới vào cơ sở dữ liệu.
    public function taoMoi(LuuHopDongRequest $yeuCau)
    {
        DB::beginTransaction();
        try {
            // Tạo bản ghi hợp đồng mới
            $hopDong = HopDong::create([
                'maPhong'     => $yeuCau->input('maPhong'),
                'maKhach'     => $yeuCau->input('maKhach'),
                'ngayBatDau'  => $yeuCau->input('ngayBatDau'),
                'ngayKetThuc' => $yeuCau->input('ngayKetThuc'),
                'giaThueChot' => $yeuCau->input('giaThueChot'),
                'tienDatCoc'  => $yeuCau->input('tienDatCoc'),
                'trangThaiHD' => 'HIEU_LUC',
            ]);

            // Thêm người ở cùng nếu có gửi kèm trong form
            if ($yeuCau->filled('nguoiOCung') && is_array($yeuCau->input('nguoiOCung'))) {
                foreach ($yeuCau->input('nguoiOCung') as $nguoi) {
                    if (!empty($nguoi['hoTen'])) {
                        NguoiOCung::create([
                            'maHopDong'   => $hopDong->maHopDong,
                            'hoTen'       => trim($nguoi['hoTen']),
                            'soCCCD'      => !empty($nguoi['soCCCD']) ? trim($nguoi['soCCCD']) : null,
                            'soDienThoai' => !empty($nguoi['soDienThoai']) ? trim($nguoi['soDienThoai']) : null,
                            'quanHe'      => !empty($nguoi['quanHe']) ? trim($nguoi['quanHe']) : null,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('hop-dong.chi-tiet', $hopDong->maHopDong)
                ->with('thongBaoThanhCong', 'Lập hợp đồng thuê phòng thành công. Trạng thái phòng đã chuyển sang Đang thuê.');
        } catch (\Throwable $loi) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('thongBaoLoi', 'Không thể tạo hợp đồng: ' . $loi->getMessage());
        }
    }

    // Hiển thị chi tiết hợp đồng, danh sách người ở cùng và lịch sử hóa đơn.
    public function chiTiet($maHopDong)
    {
        $hopDong = HopDong::with([
            'phongTro',
            'khachThue.nguoiDung',
            'nguoiOCung',
            'hoaDon' => function ($truyVan) {
                $truyVan->with('chiTietHoaDon.dichVu')->orderByDesc('thangNam');
            }
        ])->findOrFail($maHopDong);

        return view('quan-ly-hop-dong.chi-tiet', compact('hopDong'));
    }

    // Thêm người ở cùng vào hợp đồng đang có hiệu lực.
    public function themNguoiOCung(ThemNguoiOCungRequest $yeuCau, $maHopDong)
    {
        $hopDong = HopDong::findOrFail($maHopDong);

        if ($hopDong->trangThaiHD === 'DA_KET_THUC') {
            return redirect()->back()->with('thongBaoLoi', 'Hợp đồng này đã kết thúc, không thể đăng ký thêm người ở cùng.');
        }

        NguoiOCung::create([
            'maHopDong'   => $hopDong->maHopDong,
            'hoTen'       => trim($yeuCau->input('hoTen')),
            'soCCCD'      => $yeuCau->filled('soCCCD') ? trim($yeuCau->input('soCCCD')) : null,
            'soDienThoai' => $yeuCau->filled('soDienThoai') ? trim($yeuCau->input('soDienThoai')) : null,
            'quanHe'      => $yeuCau->filled('quanHe') ? trim($yeuCau->input('quanHe')) : null,
        ]);

        return redirect()->route('hop-dong.chi-tiet', $maHopDong)
            ->with('thongBaoThanhCong', 'Đã thêm người ở cùng vào hợp đồng.');
    }

    // Xóa người ở cùng khỏi hợp đồng.
    public function xoaNguoiOCung($maHopDong, $maNguoiO)
    {
        $nguoiO = NguoiOCung::where('maHopDong', $maHopDong)
            ->where('maNguoiO', $maNguoiO)
            ->firstOrFail();

        $nguoiO->delete();

        return redirect()->route('hop-dong.chi-tiet', $maHopDong)
            ->with('thongBaoThanhCong', 'Đã xóa người ở cùng khỏi hợp đồng.');
    }

    // Kết thúc / Thanh lý hợp đồng thuê phòng.
    public function ketThuc(KetThucHopDongRequest $yeuCau, $maHopDong)
    {
        $hopDong = HopDong::findOrFail($maHopDong);

        if ($hopDong->trangThaiHD === 'DA_KET_THUC') {
            return redirect()->back()->with('thongBaoLoi', 'Hợp đồng này đã kết thúc trước đó.');
        }

        $hopDong->update([
            'trangThaiHD' => 'DA_KET_THUC',
            'ngayKetThuc' => $yeuCau->input('ngayKetThuc'),
            'tienHoanCoc' => $yeuCau->input('tienHoanCoc', 0),
        ]);

        return redirect()->route('hop-dong.chi-tiet', $maHopDong)
            ->with('thongBaoThanhCong', 'Đã kết thúc hợp đồng thành công. Trạng thái phòng đã tự động hoàn về Trống.');
    }
}
