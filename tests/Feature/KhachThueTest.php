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
}
