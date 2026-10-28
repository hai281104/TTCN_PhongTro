<?php

namespace Tests\Feature;

use App\Models\NguoiDung;
use Tests\TestCase;

class DangNhapTest extends TestCase
{
    public function test_hien_thi_trang_dang_nhap_thanh_cong()
    {
        $phanHoi = $this->get('/dang-nhap');

        $phanHoi->assertStatus(200);
        $phanHoi->assertSee('Quản Lý Nhà Trọ');
        $phanHoi->assertSee('Địa chỉ Email');
        $phanHoi->assertSee('Mật khẩu');
    }

    public function test_dang_nhap_that_bai_khi_de_trong_du_lieu()
    {
        $phanHoi = $this->post('/dang-nhap', [
            'email' => '',
            'matKhau' => '',
        ]);

        $phanHoi->assertSessionHasErrors(['email', 'matKhau']);
    }

    public function test_dang_nhap_that_bai_khi_sai_mat_khau()
    {
        $phanHoi = $this->post('/dang-nhap', [
            'email' => 'chutro@example.com',
            'matKhau' => 'matkhausai123',
        ]);

        $phanHoi->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_dang_nhap_that_bai_khi_tai_khoan_bi_khoa()
    {
        $phanHoi = $this->post('/dang-nhap', [
            'email' => 'taikhoankhoa@example.com',
            'matKhau' => '123456',
        ]);

        $phanHoi->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_chu_tro_dang_nhap_thanh_cong_va_dieu_huong_ve_trang_phong_tro()
    {
        $phanHoi = $this->post('/dang-nhap', [
            'email' => 'chutro@example.com',
            'matKhau' => '123456',
        ]);

        $phanHoi->assertRedirect('/phong-tro');
        $this->assertAuthenticated();

        $nguoiDung = auth()->user();
        $this->assertEquals('CHU_TRO', $nguoiDung->vaiTro);
    }

    public function test_khach_thue_dang_nhap_thanh_cong_va_dieu_huong_ve_trang_khach_thue()
    {
        $phanHoi = $this->post('/dang-nhap', [
            'email' => 'nguyenvana@example.com',
            'matKhau' => '123456',
        ]);

        $phanHoi->assertRedirect('/khach-thue/phong-cua-toi');
        $this->assertAuthenticated();

        $nguoiDung = auth()->user();
        $this->assertEquals('KHACH_THUE', $nguoiDung->vaiTro);
    }

    public function test_khach_thue_khong_the_truy_cap_trang_chu_tro()
    {
        $khachThue = NguoiDung::where('email', 'nguyenvana@example.com')->first();

        $phanHoi = $this->actingAs($khachThue)->get('/phong-tro');

        $phanHoi->assertStatus(403);
    }

    public function test_chu_tro_khong_the_truy_cap_trang_khach_thue()
    {
        $chuTro = NguoiDung::where('email', 'chutro@example.com')->first();

        $phanHoi = $this->actingAs($chuTro)->get('/khach-thue/phong-cua-toi');

        $phanHoi->assertStatus(403);
    }

    public function test_dang_xuat_thanh_cong()
    {
        $chuTro = NguoiDung::where('email', 'chutro@example.com')->first();

        $phanHoi = $this->actingAs($chuTro)->post('/dang-xuat');

        $phanHoi->assertRedirect('/dang-nhap');
        $this->assertGuest();
    }

    public function test_gioi_han_tan_suat_dang_nhap_khi_thu_sai_nhieu_lan()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/dang-nhap', [
                'email' => 'spam@example.com',
                'matKhau' => 'matkhausai',
            ]);
        }

        $phanHoi = $this->post('/dang-nhap', [
            'email' => 'spam@example.com',
            'matKhau' => 'matkhausai',
        ]);

        $phanHoi->assertSessionHasErrors(['email']);
        $errors = session('errors')->get('email');
        $this->assertStringContainsString('quá nhiều lần', $errors[0]);
    }
}
