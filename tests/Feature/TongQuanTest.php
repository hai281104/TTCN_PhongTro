<?php

namespace Tests\Feature;

use App\Models\NguoiDung;
use Tests\TestCase;

class TongQuanTest extends TestCase
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

    public function test_chua_dang_nhap_bi_chuyen_huong_ve_dang_nhap()
    {
        $phanHoi = $this->get('/khach-thue/phong-cua-toi');
        $phanHoi->assertRedirect('/dang-nhap');

        $phanHoiTongQuan = $this->get('/tong-quan');
        $phanHoiTongQuan->assertRedirect('/dang-nhap');
    }

    public function test_chu_tro_khong_the_truy_cap_trang_phong_cua_khach()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phanHoi = $this->actingAs($chuTro)->get('/khach-thue/phong-cua-toi');
        $phanHoi->assertStatus(403);
    }

    public function test_khach_thue_khong_the_truy_cap_tong_quan_chu_tro()
    {
        $khachThue = $this->layTaiKhoanKhachThue();
        $phanHoi = $this->actingAs($khachThue)->get('/tong-quan');
        $phanHoi->assertStatus(403);
    }

    public function test_chu_tro_xem_duoc_thong_ke_tong_quan_tu_database()
    {
        $chuTro = $this->layTaiKhoanChuTro();
        $phanHoi = $this->actingAs($chuTro)->get('/tong-quan');

        $phanHoi->assertStatus(200);
        $phanHoi->assertSee('Tổng số phòng');
        $phanHoi->assertSee('Phòng đang thuê');
        $phanHoi->assertSee('Phòng trống');
        $phanHoi->assertSee('Công nợ chưa thu');
        $phanHoi->assertSee('Theo dõi phòng trọ');
    }

    public function test_khach_thue_co_hop_dong_xem_duoc_thong_tin_phong_va_hoa_don_tu_database()
    {
        $khachThue = $this->layTaiKhoanKhachThue();
        $phanHoi = $this->actingAs($khachThue)->get('/khach-thue/phong-cua-toi');

        $phanHoi->assertStatus(200);
        $phanHoi->assertSee('Cổng Dành Cho Khách Thuê');
        $phanHoi->assertSee('Nguyễn Văn A');
        $phanHoi->assertSee('Thông tin hợp đồng thuê');
        $phanHoi->assertSee('Chỉ số điện nước gần nhất');
        $phanHoi->assertSee('Lịch sử hóa đơn tiền phòng');
    }

    public function test_khach_thue_chua_co_hop_dong_hien_thi_thong_bao_chua_co_hop_dong()
    {
        $taiKhoanMoi = NguoiDung::firstOrCreate(
            ['email' => 'khachchuao@example.com'],
            [
                'matKhau' => bcrypt('123456'),
                'vaiTro' => 'KHACH_THUE',
                'dangHoatDong' => 1,
            ]
        );

        $phanHoi = $this->actingAs($taiKhoanMoi)->get('/khach-thue/phong-cua-toi');

        $phanHoi->assertStatus(200);
        $phanHoi->assertSee('Chưa có hợp đồng thuê phòng hiệu lực');
    }
}
