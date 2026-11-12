<?php

namespace Tests\Feature;

use App\Models\HopDong;
use App\Models\KhachThue;
use App\Models\NguoiDung;
use App\Models\PhongTro;
use Tests\TestCase;

class KhachThueTest extends TestCase
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

    protected function taoSoCCCDNgauNhien()
    {
        return '079' . rand(100000000, 999999999);
    }

    public function test_chua_dang_nhap_khong_the_truy_cap_danh_sach_khach()
    {
        $phanHoi = $this->get('/khach-thue');
        $phanHoi->assertRedirect('/dang-nhap');
    }

    public function test_khach_thue_khong_the_truy_cap_danh_sach_khach()
    {
        $khachThue = $this->layTaiKhoanKhachThue();
        $phanHoi = $this->actingAs($khachThue)->get('/khach-thue');
        $phanHoi->assertStatus(403);
    }

    public function test_chu_tro_xem_duoc_danh_sach_khach_thue()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phanHoi = $this->actingAs($chuTro)->get('/khach-thue');

        $phanHoi->assertStatus(200);
        $phanHoi->assertSee('Quản Lý Khách Thuê');
        $phanHoi->assertSee('Thêm hồ sơ khách');
    }

    public function test_chu_tro_them_khach_thue_moi_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $soCCCD = $this->taoSoCCCDNgauNhien();

        $phanHoi = $this->actingAs($chuTro)->post('/khach-thue', [
            'hoTen' => 'Trần Văn Test',
            'soCCCD' => $soCCCD,
            'soDienThoai' => '0987654321',
            'diaChi' => 'Thành phố Thủ Đức, TP. Hồ Chí Minh',
        ]);

        $phanHoi->assertRedirect('/khach-thue');
        $this->assertDatabaseHas('KhachThue', [
            'hoTen' => 'Trần Văn Test',
            'soCCCD' => $soCCCD,
            'soDienThoai' => '0987654321',
            'dangHoatDong' => 1,
        ]);
    }

    public function test_them_khach_thue_kem_tao_tai_khoan_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $soCCCD = $this->taoSoCCCDNgauNhien();
        $emailMoi = 'khachtest' . rand(1000, 9999) . '@example.com';

        $phanHoi = $this->actingAs($chuTro)->post('/khach-thue', [
            'hoTen' => 'Lê Thị Tài Khoản',
            'soCCCD' => $soCCCD,
            'soDienThoai' => '0912345678',
            'diaChi' => 'Quận 1, TP. Hồ Chí Minh',
            'taoTaiKhoan' => 1,
            'email' => $emailMoi,
            'matKhau' => '123456',
        ]);

        $phanHoi->assertRedirect('/khach-thue');
        $this->assertDatabaseHas('NguoiDung', [
            'email' => $emailMoi,
            'vaiTro' => 'KHACH_THUE',
        ]);
        $this->assertDatabaseHas('KhachThue', [
            'soCCCD' => $soCCCD,
            'hoTen' => 'Lê Thị Tài Khoản',
        ]);
    }

    public function test_them_khach_that_bai_khi_thieu_ho_ten()
    {
        $chuTro = $this->layTaiKhoanChuTro();

        $phanHoi = $this->actingAs($chuTro)->post('/khach-thue', [
            'hoTen' => '',
            'soCCCD' => $this->taoSoCCCDNgauNhien(),
            'soDienThoai' => '0901234567',
        ]);

        $phanHoi->assertSessionHasErrors(['hoTen']);
    }

    public function test_them_khach_that_bai_khi_so_cccd_khong_du_12_so()
    {
        $chuTro = $this->layTaiKhoanChuTro();

        $phanHoi = $this->actingAs($chuTro)->post('/khach-thue', [
            'hoTen' => 'Nguyễn Sai CCCD',
            'soCCCD' => '123456789',
            'soDienThoai' => '0901234567',
        ]);

        $phanHoi->assertSessionHasErrors(['soCCCD']);
    }

    public function test_chu_tro_cap_nhat_thong_tin_khach_thue_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $khach = KhachThue::create([
            'hoTen' => 'Phạm Văn Cần Sửa',
            'soCCCD' => $this->taoSoCCCDNgauNhien(),
            'soDienThoai' => '0933333333',
            'diaChi' => 'Bình Thạnh',
            'dangHoatDong' => 1,
        ]);

        $phanHoi = $this->actingAs($chuTro)->put("/khach-thue/{$khach->maKhach}", [
            'hoTen' => 'Phạm Văn Đã Sửa',
            'soCCCD' => $khach->soCCCD,
            'soDienThoai' => '0944444444',
            'diaChi' => 'Gò Vấp',
        ]);

        $phanHoi->assertRedirect('/khach-thue');
        $this->assertDatabaseHas('KhachThue', [
            'maKhach' => $khach->maKhach,
            'hoTen' => 'Phạm Văn Đã Sửa',
            'soDienThoai' => '0944444444',
            'diaChi' => 'Gò Vấp',
        ]);
    }

    public function test_chu_tro_cap_tai_khoan_cho_khach_thue_chua_co_tai_khoan()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $khach = KhachThue::create([
            'hoTen' => 'Hoàng Văn Chưa Có TK',
            'soCCCD' => $this->taoSoCCCDNgauNhien(),
            'soDienThoai' => '0955555555',
            'dangHoatDong' => 1,
        ]);

        $emailMoi = 'hoangvan' . rand(1000, 9999) . '@example.com';

        $phanHoi = $this->actingAs($chuTro)->post("/khach-thue/{$khach->maKhach}/cap-tai-khoan", [
            'email' => $emailMoi,
            'matKhau' => 'matkhau123',
        ]);

        $phanHoi->assertRedirect('/khach-thue');
        $khachMoiCap = $khach->fresh();
        $this->assertNotNull($khachMoiCap->maNguoiDung);
        $this->assertDatabaseHas('NguoiDung', [
            'email' => $emailMoi,
            'vaiTro' => 'KHACH_THUE',
        ]);
    }

    public function test_chu_tro_xoa_mem_khach_thue_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $khach = KhachThue::create([
            'hoTen' => 'Đặng Văn Cần Xóa',
            'soCCCD' => $this->taoSoCCCDNgauNhien(),
            'soDienThoai' => '0966666666',
            'dangHoatDong' => 1,
        ]);

        $phanHoi = $this->actingAs($chuTro)->delete("/khach-thue/{$khach->maKhach}");

        $phanHoi->assertRedirect('/khach-thue');
        $this->assertDatabaseHas('KhachThue', [
            'maKhach' => $khach->maKhach,
            'dangHoatDong' => 0,
        ]);
    }

    public function test_chu_tro_them_khach_thue_voi_day_du_thong_tin_nhan_than_va_tam_tru_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $soCCCD = $this->taoSoCCCDNgauNhien();

        $phanHoi = $this->actingAs($chuTro)->post('/khach-thue', [
            'hoTen' => 'Ngô Thị Hồ Sơ Đầy Đủ',
            'soCCCD' => $soCCCD,
            'soDienThoai' => '0977889900',
            'diaChi' => 'Đà Nẵng',
            'ngaySinh' => '1998-05-20',
            'gioiTinh' => 'NU',
            'ngayCapCCCD' => '2021-08-15',
            'noiCapCCCD' => 'Cục Cảnh sát QLHC về TTXH',
            'ngheNghiep' => 'Lập trình viên',
            'bienSoXe' => '43-C1 999.88',
            'nguoiLienHeKhanCap' => 'Ngô Văn Bố',
            'sdtKhanCap' => '0905123456',
            'quanHeKhanCap' => 'Bố ruột',
            'daDangKyTamTru' => 1,
        ]);

        $phanHoi->assertRedirect('/khach-thue');
        $this->assertDatabaseHas('KhachThue', [
            'hoTen' => 'Ngô Thị Hồ Sơ Đầy Đủ',
            'soCCCD' => $soCCCD,
            'gioiTinh' => 'NU',
            'ngheNghiep' => 'Lập trình viên',
            'bienSoXe' => '43-C1 999.88',
            'nguoiLienHeKhanCap' => 'Ngô Văn Bố',
            'daDangKyTamTru' => 1,
            'dangHoatDong' => 1,
        ]);
    }

    public function test_chu_tro_doi_trang_thai_tam_tru_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $khach = KhachThue::create([
            'hoTen' => 'Vũ Thị Tạm Trú',
            'soCCCD' => $this->taoSoCCCDNgauNhien(),
            'soDienThoai' => '0933887766',
            'daDangKyTamTru' => 0,
            'dangHoatDong' => 1,
        ]);

        $phanHoi = $this->actingAs($chuTro)->patch("/khach-thue/{$khach->maKhach}/tam-tru");

        $phanHoi->assertSessionHas('thongBaoThanhCong');
        $this->assertDatabaseHas('KhachThue', [
            'maKhach' => $khach->maKhach,
            'daDangKyTamTru' => 1,
        ]);

        // Đổi ngược lại về 0
        $this->actingAs($chuTro)->patch("/khach-thue/{$khach->maKhach}/tam-tru");
        $this->assertDatabaseHas('KhachThue', [
            'maKhach' => $khach->maKhach,
            'daDangKyTamTru' => 0,
        ]);
    }

    public function test_them_khach_thue_kem_xep_phong_o_cung_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();

        // Bước 1: Tạo phòng
        $phong = PhongTro::create([
            'soPhong' => 'P_OCUNG_' . substr(md5(uniqid()), 0, 5),
            'soNguoiToiDa' => 3,
            'donGiaMacDinh' => 3000000,
            'dangHoatDong' => 1,
        ]);

        // Bước 2: Nhập hồ sơ khách đại diện và khách ở cùng trước
        $khachDaiDien = KhachThue::create([
            'hoTen' => 'Người Đại Diện ' . rand(100, 999),
            'soCCCD' => $this->taoSoCCCDNgauNhien(),
            'soDienThoai' => '0988111222',
            'dangHoatDong' => 1,
        ]);

        $soCCCDMoi = $this->taoSoCCCDNgauNhien();
        $this->actingAs($chuTro)->post('/khach-thue', [
            'hoTen' => 'Khách Ở Cùng Test',
            'soCCCD' => $soCCCDMoi,
            'soDienThoai' => '0977222333',
            'diaChi' => 'Hà Nội',
        ])->assertRedirect('/khach-thue');

        $khachMoi = KhachThue::where('soCCCD', $soCCCDMoi)->firstOrFail();

        // Bước 3: Ký hợp đồng cho người đại diện (khởi tạo 1 người)
        $hopDong = HopDong::create([
            'maPhong' => $phong->maPhong,
            'maKhach' => $khachDaiDien->maKhach,
            'soLuongNguoi' => 1,
            'ngayBatDau' => now()->toDateString(),
            'giaThueChot' => 3000000,
            'tienDatCoc' => 3000000,
            'trangThaiHD' => 'HIEU_LUC',
        ]);

        // Bước 4: Thêm khách ở cùng tại mục Quản lý khách ở cùng
        $phanHoi = $this->actingAs($chuTro)->post('/khach-o-cung', [
            'maHopDong' => $hopDong->maHopDong,
            'maKhach' => $khachMoi->maKhach,
            'ghiChu' => 'Bạn cùng lớp',
        ]);

        $phanHoi->assertSessionHas('thongBaoThanhCong');

        $this->assertDatabaseHas('NguoiOCung', [
            'maHopDong' => $hopDong->maHopDong,
            'maKhach' => $khachMoi->maKhach,
            'ghiChu' => 'Bạn cùng lớp',
            'dangHoatDong' => 1,
        ]);

        $hopDong->refresh();
        $this->assertEquals(2, $hopDong->soLuongNguoi);
    }

    public function test_them_khach_o_cung_that_bai_khi_phong_da_du_nguoi()
    {
        $chuTro = $this->layTaiKhoanChuTro();

        $phong = PhongTro::create([
            'soPhong' => 'P_FULL_' . substr(md5(uniqid()), 0, 5),
            'soNguoiToiDa' => 1, // Sức chứa tối đa 1 người
            'donGiaMacDinh' => 2500000,
            'dangHoatDong' => 1,
        ]);

        $khachDaiDien = KhachThue::create([
            'hoTen' => 'Đại Diện Phòng Đơn',
            'soCCCD' => $this->taoSoCCCDNgauNhien(),
            'soDienThoai' => '0988444555',
            'dangHoatDong' => 1,
        ]);

        $khachOCung = KhachThue::create([
            'hoTen' => 'Khách Bị Chặn Vì Đủ Chỗ',
            'soCCCD' => $this->taoSoCCCDNgauNhien(),
            'soDienThoai' => '0977666777',
            'dangHoatDong' => 1,
        ]);

        $hopDong = HopDong::create([
            'maPhong' => $phong->maPhong,
            'maKhach' => $khachDaiDien->maKhach,
            'soLuongNguoi' => 1, // Đã đủ 1 người
            'ngayBatDau' => now()->toDateString(),
            'giaThueChot' => 2500000,
            'tienDatCoc' => 2500000,
            'trangThaiHD' => 'HIEU_LUC',
        ]);

        $phanHoi = $this->actingAs($chuTro)->post('/khach-o-cung', [
            'maHopDong' => $hopDong->maHopDong,
            'maKhach' => $khachOCung->maKhach,
        ]);

        $phanHoi->assertSessionHasErrors(['maHopDong']);
        $this->assertDatabaseMissing('NguoiOCung', [
            'maHopDong' => $hopDong->maHopDong,
            'maKhach' => $khachOCung->maKhach,
        ]);
    }

    public function test_cap_nhat_khach_o_cung_chuyen_phong_va_roi_phong()
    {
        $chuTro = $this->layTaiKhoanChuTro();

        $phong = PhongTro::create([
            'soPhong' => 'P_ROI_' . substr(md5(uniqid()), 0, 5),
            'soNguoiToiDa' => 3,
            'donGiaMacDinh' => 3000000,
            'dangHoatDong' => 1,
        ]);

        $khachDaiDien = KhachThue::create([
            'hoTen' => 'Đại diện HĐ Rời',
            'soCCCD' => $this->taoSoCCCDNgauNhien(),
            'soDienThoai' => '0988777666',
            'dangHoatDong' => 1,
        ]);

        $hopDong = HopDong::create([
            'maPhong' => $phong->maPhong,
            'maKhach' => $khachDaiDien->maKhach,
            'soLuongNguoi' => 2,
            'ngayBatDau' => now()->toDateString(),
            'giaThueChot' => 3000000,
            'tienDatCoc' => 3000000,
            'trangThaiHD' => 'HIEU_LUC',
        ]);

        $khachOCung = KhachThue::create([
            'hoTen' => 'Khách Sắp Rời Phòng',
            'soCCCD' => $this->taoSoCCCDNgauNhien(),
            'soDienThoai' => '0988999000',
            'dangHoatDong' => 1,
        ]);

        $banOCung = \App\Models\NguoiOCung::create([
            'maHopDong' => $hopDong->maHopDong,
            'maKhach' => $khachOCung->maKhach,
            'dangHoatDong' => 1,
        ]);

        // Cho khách ở cùng rời phòng tại mục Quản lý khách ở cùng
        $phanHoi = $this->actingAs($chuTro)->patch("/khach-o-cung/{$banOCung->maNguoiOCung}/roi-phong");

        $phanHoi->assertSessionHas('thongBaoThanhCong');

        $this->assertDatabaseHas('NguoiOCung', [
            'maNguoiOCung' => $banOCung->maNguoiOCung,
            'dangHoatDong' => 0,
        ]);

        $hopDong->refresh();
        $this->assertEquals(1, $hopDong->soLuongNguoi);
    }

    public function test_khong_the_gan_dai_dien_hop_dong_lam_nguoi_o_cung_phong_khac()
    {
        $chuTro = $this->layTaiKhoanChuTro();

        $phong1 = PhongTro::create(['soPhong' => 'P1_' . rand(100, 999), 'soNguoiToiDa' => 2, 'donGiaMacDinh' => 3000000, 'dangHoatDong' => 1]);
        $phong2 = PhongTro::create(['soPhong' => 'P2_' . rand(100, 999), 'soNguoiToiDa' => 2, 'donGiaMacDinh' => 3000000, 'dangHoatDong' => 1]);

        $khachDaiDien1 = KhachThue::create(['hoTen' => 'Đại Diện P1', 'soCCCD' => $this->taoSoCCCDNgauNhien(), 'soDienThoai' => '0911000111', 'dangHoatDong' => 1]);
        $khachDaiDien2 = KhachThue::create(['hoTen' => 'Đại Diện P2', 'soCCCD' => $this->taoSoCCCDNgauNhien(), 'soDienThoai' => '0911000222', 'dangHoatDong' => 1]);

        $hd1 = HopDong::create(['maPhong' => $phong1->maPhong, 'maKhach' => $khachDaiDien1->maKhach, 'soLuongNguoi' => 1, 'ngayBatDau' => now(), 'giaThueChot' => 3000000, 'tienDatCoc' => 3000000, 'trangThaiHD' => 'HIEU_LUC']);
        $hd2 = HopDong::create(['maPhong' => $phong2->maPhong, 'maKhach' => $khachDaiDien2->maKhach, 'soLuongNguoi' => 1, 'ngayBatDau' => now(), 'giaThueChot' => 3000000, 'tienDatCoc' => 3000000, 'trangThaiHD' => 'HIEU_LUC']);

        // Khách 1 đang là đại diện HĐ1, cố gán vào HĐ2 làm người ở cùng
        $phanHoi = $this->actingAs($chuTro)->post('/khach-o-cung', [
            'maHopDong' => $hd2->maHopDong,
            'maKhach' => $khachDaiDien1->maKhach,
        ]);

        $phanHoi->assertSessionHasErrors(['maKhach']);
    }
}