<?php

namespace Tests\Feature;

use App\Models\HopDong;
use App\Models\KhachThue;
use App\Models\NguoiDung;
use App\Models\PhongTro;
use Tests\TestCase;

class PhongTroTest extends TestCase
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

    public function test_chua_dang_nhap_khong_the_truy_cap_danh_sach_phong()
    {
        $phanHoi = $this->get('/phong-tro');
        $phanHoi->assertRedirect('/dang-nhap');
    }

    public function test_khach_thue_khong_the_truy_cap_danh_sach_phong()
    {
        $khachThue = $this->layTaiKhoanKhachThue();
        $phanHoi = $this->actingAs($khachThue)->get('/phong-tro');
        $phanHoi->assertStatus(403);
    }

    public function test_chu_tro_xem_duoc_danh_sach_phong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phanHoi = $this->actingAs($chuTro)->get('/phong-tro');

        $phanHoi->assertStatus(200);
        $phanHoi->assertSee('Quản Lý Phòng Trọ');
        $phanHoi->assertSee('Thêm phòng mới');
    }

    public function test_chu_tro_them_phong_moi_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $soPhongMoi = 'P.TEST.' . rand(100, 999);

        $phanHoi = $this->actingAs($chuTro)->post('/phong-tro', [
            'soPhong' => $soPhongMoi,
            'donGiaMacDinh' => 3000000,
            'ghiChu' => 'Phòng kiểm thử tự động',
        ]);

        $phanHoi->assertRedirect('/phong-tro');
        $this->assertDatabaseHas('PhongTro', [
            'soPhong' => $soPhongMoi,
            'donGiaMacDinh' => 3000000,
            'trangThaiPhong' => 'TRONG',
            'dangHoatDong' => 1,
        ]);
    }

    public function test_them_phong_that_bai_khi_thieu_so_phong()
    {
        $chuTro = $this->layTaiKhoanChuTro();

        $phanHoi = $this->actingAs($chuTro)->post('/phong-tro', [
            'soPhong' => '',
            'donGiaMacDinh' => 2500000,
        ]);

        $phanHoi->assertSessionHasErrors(['soPhong']);
    }

    public function test_them_phong_that_bai_khi_don_gia_am()
    {
        $chuTro = $this->layTaiKhoanChuTro();

        $phanHoi = $this->actingAs($chuTro)->post('/phong-tro', [
            'soPhong' => 'P.AM.' . rand(100, 999),
            'donGiaMacDinh' => -500000,
        ]);

        $phanHoi->assertSessionHasErrors(['donGiaMacDinh']);
    }

    public function test_chu_tro_cap_nhat_thong_tin_phong_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phong = PhongTro::create([
            'soPhong' => 'P.SUA.' . rand(100, 999),
            'donGiaMacDinh' => 2000000,
            'trangThaiPhong' => 'TRONG',
            'dangHoatDong' => 1,
        ]);

        $phanHoi = $this->actingAs($chuTro)->put("/phong-tro/{$phong->maPhong}", [
            'soPhong' => $phong->soPhong,
            'donGiaMacDinh' => 2700000,
            'ghiChu' => 'Đã nâng cấp nội thất',
        ]);

        $phanHoi->assertRedirect('/phong-tro');
        $this->assertDatabaseHas('PhongTro', [
            'maPhong' => $phong->maPhong,
            'donGiaMacDinh' => 2700000,
            'ghiChu' => 'Đã nâng cấp nội thất',
        ]);
    }

    public function test_chu_tro_chuyen_doi_trang_thai_phong_trong_sang_sua_chua()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phong = PhongTro::create([
            'soPhong' => 'P.TT.' . rand(100, 999),
            'donGiaMacDinh' => 2200000,
            'trangThaiPhong' => 'TRONG',
            'dangHoatDong' => 1,
        ]);

        $phanHoi = $this->actingAs($chuTro)->patch("/phong-tro/{$phong->maPhong}/trang-thai");

        $phanHoi->assertSessionHas('thongBaoThanhCong');
        $this->assertDatabaseHas('PhongTro', [
            'maPhong' => $phong->maPhong,
            'trangThaiPhong' => 'DANG_SUA_CHUA',
        ]);

        // Đổi ngược lại về TRONG
        $this->actingAs($chuTro)->patch("/phong-tro/{$phong->maPhong}/trang-thai");
        $this->assertDatabaseHas('PhongTro', [
            'maPhong' => $phong->maPhong,
            'trangThaiPhong' => 'TRONG',
        ]);
    }

    public function test_chu_tro_xoa_mem_phong_tro_thanh_cong()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phong = PhongTro::create([
            'soPhong' => 'P.DEL.' . rand(100, 999),
            'donGiaMacDinh' => 2000000,
            'trangThaiPhong' => 'TRONG',
            'dangHoatDong' => 1,
        ]);

        $phanHoi = $this->actingAs($chuTro)->delete("/phong-tro/{$phong->maPhong}");

        $phanHoi->assertRedirect('/phong-tro');
        $this->assertDatabaseHas('PhongTro', [
            'maPhong' => $phong->maPhong,
            'dangHoatDong' => 0,
        ]);
    }
}

