<?php

namespace Tests\Feature;

use App\Models\HopDong;
use App\Models\KhachThue;
use App\Models\NguoiDung;
use App\Models\NguoiOCung;
use App\Models\PhongTro;
use Tests\TestCase;

class HopDongTest extends TestCase
{
    protected function layTaiKhoanChuTro()
    {
        return NguoiDung::firstOrCreate(
            ['email' => 'chutro@example.com'],
            [
                'matKhau' => bcrypt('123456'),
                'vaiTro' => 'CHU_TRO',
                'dangHoatDong' => 1,
            ]
        );
    }

    protected function layTaiKhoanKhachThue()
    {
        return NguoiDung::firstOrCreate(
            ['email' => 'nguyenvana@example.com'],
            [
                'matKhau' => bcrypt('123456'),
                'vaiTro' => 'KHACH_THUE',
                'dangHoatDong' => 1,
            ]
        );
    }

    protected function taoKhachThueMau()
    {
        return KhachThue::create([
            'hoTen' => 'Khách Test Hợp Đồng ' . rand(1000, 9999),
            'soCCCD' => '079' . rand(100000000, 999999999),
            'soDienThoai' => '09' . rand(10000000, 99999999),
            'diaChi' => 'TP. Hồ Chí Minh',
            'dangHoatDong' => 1,
        ]);
    }

    protected function taoPhongTroTrongMau()
    {
        return PhongTro::create([
            'soPhong' => 'P' . rand(1000, 9999) . '_' . substr(uniqid(), -4),
            'donGiaMacDinh' => 3000000,
            'trangThaiPhong' => 'TRONG',
            'dangHoatDong' => 1,
        ]);
    }

    public function test_chua_dang_nhap_khong_the_truy_cap_danh_sach_hop_dong()
    {
        $phanHoi = $this->get('/hop-dong');
        $phanHoi->assertRedirect('/dang-nhap');
    }

    public function test_khach_thue_khong_the_truy_cap_danh_sach_hop_dong()
    {
        $khachThue = $this->layTaiKhoanKhachThue();
        $phanHoi = $this->actingAs($khachThue)->get('/hop-dong');
        $phanHoi->assertStatus(403);
    }

    public function test_chu_tro_xem_duoc_danh_sach_hop_dong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phanHoi = $this->actingAs($chuTro)->get('/hop-dong');

        $phanHoi->assertStatus(200);
        $phanHoi->assertSee('Quản Lý Hợp Đồng Thuê Phòng');
        $phanHoi->assertSee('Lập hợp đồng mới');
        $phanHoi->assertSee('Tổng số hợp đồng');
    }

    public function test_chu_tro_lap_hop_dong_moi_thanh_cong_va_phong_chuyen_sang_dang_thue()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phong = $this->taoPhongTroTrongMau();
        $khach = $this->taoKhachThueMau();

        $phanHoi = $this->actingAs($chuTro)->post('/hop-dong', [
            'maPhong' => $phong->maPhong,
            'maKhach' => $khach->maKhach,
            'ngayBatDau' => '2026-11-01',
            'ngayKetThuc' => '2027-11-01',
            'giaThueChot' => 3000000,
            'tienDatCoc' => 3000000,
            'nguoiOCung' => [
                [
                    'hoTen' => 'Người Ở Cùng Test',
                    'soCCCD' => '079987654321',
                    'soDienThoai' => '0911223344',
                    'quanHe' => 'Bạn',
                ]
            ],
        ]);

        $this->assertDatabaseHas('HopDong', [
            'maPhong' => $phong->maPhong,
            'maKhach' => $khach->maKhach,
            'trangThaiHD' => 'HIEU_LUC',
        ]);

        $hopDongMoi = HopDong::where('maPhong', $phong->maPhong)->first();
        $phanHoi->assertRedirect("/hop-dong/{$hopDongMoi->maHopDong}");

        // Kiểm tra người ở cùng được lưu
        $this->assertDatabaseHas('NguoiOCung', [
            'maHopDong' => $hopDongMoi->maHopDong,
            'hoTen' => 'Người Ở Cùng Test',
        ]);

        // Kiểm tra trigger CSDL tự động chuyển trạng thái phòng sang DANG_THUE
        $phongCapNhat = $phong->fresh();
        $this->assertEquals('DANG_THUE', $phongCapNhat->trangThaiPhong);
    }

    public function test_lap_hop_dong_voi_gia_thue_va_tien_coc_dinh_dang_tien_te_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phong = $this->taoPhongTroTrongMau();
        $khach = $this->taoKhachThueMau();

        $phanHoi = $this->actingAs($chuTro)->post('/hop-dong', [
            'maPhong' => $phong->maPhong,
            'maKhach' => $khach->maKhach,
            'ngayBatDau' => '2026-11-01',
            'ngayKetThuc' => '2027-11-01',
            'giaThueChot' => '3.200.000',
            'tienDatCoc' => '3.200.000',
        ]);

        $this->assertDatabaseHas('HopDong', [
            'maPhong' => $phong->maPhong,
            'maKhach' => $khach->maKhach,
            'giaThueChot' => 3200000,
            'tienDatCoc' => 3200000,
            'trangThaiHD' => 'HIEU_LUC',
        ]);

        $hopDongMoi = HopDong::where('maPhong', $phong->maPhong)->first();
        $phanHoi->assertRedirect("/hop-dong/{$hopDongMoi->maHopDong}");
    }

    public function test_lap_hop_dong_that_bai_khi_thieu_truong_bat_buoc()
    {
        $chuTro = $this->layTaiKhoanChuTro();

        $phanHoi = $this->actingAs($chuTro)->post('/hop-dong', [
            'maPhong' => '',
            'maKhach' => '',
            'ngayBatDau' => '',
            'giaThueChot' => '',
            'tienDatCoc' => '',
        ]);

        $phanHoi->assertSessionHasErrors(['maPhong', 'maKhach', 'ngayBatDau', 'giaThueChot', 'tienDatCoc']);
    }

    public function test_lap_hop_dong_that_bai_khi_phong_dang_co_nguoi_thue()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phongDangThue = PhongTro::where('trangThaiPhong', 'DANG_THUE')->first();

        if ($phongDangThue) {
            $khach = $this->taoKhachThueMau();

            $phanHoi = $this->actingAs($chuTro)->post('/hop-dong', [
                'maPhong' => $phongDangThue->maPhong,
                'maKhach' => $khach->maKhach,
                'ngayBatDau' => '2026-11-01',
                'giaThueChot' => 2500000,
                'tienDatCoc' => 2500000,
            ]);

            $phanHoi->assertSessionHasErrors(['maPhong']);
        }
    }

    public function test_chu_tro_xem_duoc_chi_tiet_hop_dong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $hopDong = HopDong::first();

        if ($hopDong) {
            $phanHoi = $this->actingAs($chuTro)->get("/hop-dong/{$hopDong->maHopDong}");

            $phanHoi->assertStatus(200);
            $phanHoi->assertSee('Hợp Đồng #HD-');
            $phanHoi->assertSee('Khách thuê đại diện');
        }
    }

    public function test_chu_tro_them_nguoi_o_cung_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phong = $this->taoPhongTroTrongMau();
        $khach = $this->taoKhachThueMau();

        $hopDong = HopDong::create([
            'maPhong' => $phong->maPhong,
            'maKhach' => $khach->maKhach,
            'ngayBatDau' => '2026-11-01',
            'giaThueChot' => 3000000,
            'tienDatCoc' => 3000000,
            'trangThaiHD' => 'HIEU_LUC',
        ]);

        $phanHoi = $this->actingAs($chuTro)->post("/hop-dong/{$hopDong->maHopDong}/nguoi-o-cung", [
            'hoTen' => 'Nguyễn Thị Hoa Thêm Mới',
            'quanHe' => 'Em gái',
            'soCCCD' => '079111222333',
            'soDienThoai' => '0933445566',
        ]);

        $phanHoi->assertRedirect("/hop-dong/{$hopDong->maHopDong}");
        $this->assertDatabaseHas('NguoiOCung', [
            'maHopDong' => $hopDong->maHopDong,
            'hoTen' => 'Nguyễn Thị Hoa Thêm Mới',
            'quanHe' => 'Em gái',
        ]);
    }

    public function test_chu_tro_xoa_nguoi_o_cung_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phong = $this->taoPhongTroTrongMau();
        $khach = $this->taoKhachThueMau();

        $hopDong = HopDong::create([
            'maPhong' => $phong->maPhong,
            'maKhach' => $khach->maKhach,
            'ngayBatDau' => '2026-11-01',
            'giaThueChot' => 3000000,
            'tienDatCoc' => 3000000,
            'trangThaiHD' => 'HIEU_LUC',
        ]);

        $nguoiO = NguoiOCung::create([
            'maHopDong' => $hopDong->maHopDong,
            'hoTen' => 'Người Cần Xóa',
            'quanHe' => 'Bạn',
        ]);

        $phanHoi = $this->actingAs($chuTro)->delete("/hop-dong/{$hopDong->maHopDong}/nguoi-o-cung/{$nguoiO->maNguoiO}");

        $phanHoi->assertRedirect("/hop-dong/{$hopDong->maHopDong}");
        $this->assertDatabaseMissing('NguoiOCung', [
            'maNguoiO' => $nguoiO->maNguoiO,
        ]);
    }

    public function test_chu_tro_thanh_ly_hop_dong_thanh_cong_va_phong_tu_dong_chuyen_ve_trong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phong = $this->taoPhongTroTrongMau();
        $khach = $this->taoKhachThueMau();

        $hopDong = HopDong::create([
            'maPhong' => $phong->maPhong,
            'maKhach' => $khach->maKhach,
            'ngayBatDau' => '2026-11-01',
            'giaThueChot' => 3000000,
            'tienDatCoc' => 3000000,
            'trangThaiHD' => 'HIEU_LUC',
        ]);

        $this->assertEquals('DANG_THUE', $phong->fresh()->trangThaiPhong);

        $phanHoi = $this->actingAs($chuTro)->patch("/hop-dong/{$hopDong->maHopDong}/ket-thuc", [
            'ngayKetThuc' => '2026-11-30',
            'tienHoanCoc' => 3000000,
        ]);

        $phanHoi->assertRedirect("/hop-dong/{$hopDong->maHopDong}");
        $this->assertDatabaseHas('HopDong', [
            'maHopDong' => $hopDong->maHopDong,
            'trangThaiHD' => 'DA_KET_THUC',
            'tienHoanCoc' => 3000000,
        ]);

        // Kiểm tra trigger CSDL tự động chuyển trạng thái phòng về TRONG
        $this->assertEquals('TRONG', $phong->fresh()->trangThaiPhong);
    }
}
