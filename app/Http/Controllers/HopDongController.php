<?php

namespace App\Http\Controllers;

use App\Http\Requests\KetThucHopDongRequest;
use App\Http\Requests\LuuHopDongRequest;
use App\Models\HopDong;
use App\Models\KhachThue;
use App\Models\PhongTro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HopDongController extends Controller
{
    // Hiển thị danh sách các hợp đồng thuê phòng kèm bộ lọc và các thẻ thống kê.
    public function danhSach(Request $yeuCau)
    {
        $truyVan = HopDong::with(['phongTro.loaiPhong', 'khachThue']);

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

        // Lấy danh sách phòng trống kèm thể loại phòng để phục vụ modal tạo hợp đồng
        $danhSachPhongTrong = PhongTro::with('loaiPhong')
            ->where('dangHoatDong', 1)
            ->where('trangThaiPhong', 'TRONG')
            ->orderBy('soPhong', 'asc')
            ->get();

        // Lấy danh sách khách thuê đang hoạt động (người đại diện ký kết)
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

    // Tạo và lưu hợp đồng thuê phòng mới (1 người đại diện ký hợp đồng, lưu số lượng người ở).
    public function taoMoi(LuuHopDongRequest $yeuCau)
    {
        DB::beginTransaction();
        try {
            // Tạo bản ghi hợp đồng mới với 1 người đại diện ký hợp đồng
            $hopDong = HopDong::create([
                'maPhong'      => $yeuCau->input('maPhong'),
                'maKhach'      => $yeuCau->input('maKhach'),
                'soLuongNguoi' => $yeuCau->input('soLuongNguoi', 1),
                'ngayBatDau'   => $yeuCau->input('ngayBatDau'),
                'ngayKetThuc'  => $yeuCau->input('ngayKetThuc'),
                'giaThueChot'  => $yeuCau->input('giaThueChot'),
                'tienDatCoc'   => $yeuCau->input('tienDatCoc'),
                'trangThaiHD'  => 'HIEU_LUC',
                'ghiChu'       => $yeuCau->input('ghiChu'),
            ]);

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

    // Hiển thị chi tiết hợp đồng, thông tin người đại diện và lịch sử hóa đơn.
    public function chiTiet($maHopDong)
    {
        $hopDong = HopDong::with([
            'phongTro.loaiPhong',
            'khachThue.nguoiDung',
            'hoaDon' => function ($truyVan) {
                $truyVan->with('chiTietHoaDon.dichVu')->orderByDesc('thangNam');
            }
        ])->findOrFail($maHopDong);

        return view('quan-ly-hop-dong.chi-tiet', compact('hopDong'));
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
