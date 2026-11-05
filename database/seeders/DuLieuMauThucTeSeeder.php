<?php

namespace Database\Seeders;

use App\Models\HopDong;
use App\Models\KhachThue;
use App\Models\LoaiPhong;
use App\Models\NguoiDung;
use App\Models\NguoiOCung;
use App\Models\PhongTro;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DuLieuMauThucTeSeeder extends Seeder
{
    /**
     * Chạy Seeder xóa sạch dữ liệu cũ và tạo hơn 10 bản ghi phòng, khách, hợp đồng thực tế.
     *
     * @return void
     */
    public function run()
    {
        // Tắt tạm thời kiểm tra khóa ngoại để dọn dẹp sạch sẽ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('chitiethoadon')->truncate();
        DB::table('hoadon')->truncate();
        DB::table('chisodiennuoc')->truncate();
        DB::table('nguoiocung')->truncate();
        DB::table('hopdong')->truncate();
        DB::table('khachthue')->truncate();
        DB::table('phongtro')->truncate();

        // Xóa tài khoản khách thuê, chỉ giữ lại chủ trọ
        NguoiDung::where('vaiTro', 'KHACH_THUE')->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. TÀI KHOẢN CHỦ TRỌ VÀ TÀI KHOẢN TEST HỆ THỐNG
        $chuTro = NguoiDung::updateOrCreate(
            ['email' => 'chutro@example.com'],
            [
                'matKhau' => Hash::make('123456'),
                'vaiTro' => 'CHU_TRO',
                'dangHoatDong' => 1,
            ]
        );

        NguoiDung::updateOrCreate(
            ['email' => 'taikhoankhoa@example.com'],
            [
                'matKhau' => Hash::make('123456'),
                'vaiTro' => 'KHACH_THUE',
                'dangHoatDong' => 0,
            ]
        );

        // 2. TẠO 11 PHÒNG TRỌ THỰC TẾ TRẢI DÀI 3 TẦNG
        $danhSachPhongData = [
            // --- TẦNG 1 ---
            [
                'soPhong' => 'P101',
                'maLoaiPhong' => 4, // Studio
                'tang' => 'Tầng 1',
                'dienTich' => 28.00,
                'soNguoiToiDa' => 3,
                'donGiaMacDinh' => 4200000,
                'tienCocMacDinh' => 4200000,
                'tienNghi' => ['dieu_hoa', 'nong_lanh', 'tu_lanh', 'may_giat', 'ke_bep', 'giuong_nem', 'ban_ghe'],
                'trangThaiPhong' => 'DANG_THUE',
                'ghiChu' => 'Căn góc tầng trệt thoáng mát, view sân vườn nhỏ phía trước.',
            ],
            [
                'soPhong' => 'P102',
                'maLoaiPhong' => 2, // Gác lửng
                'tang' => 'Tầng 1',
                'dienTich' => 22.00,
                'soNguoiToiDa' => 2,
                'donGiaMacDinh' => 3200000,
                'tienCocMacDinh' => 3200000,
                'tienNghi' => ['dieu_hoa', 'nong_lanh', 'gac_lung', 'ke_bep', 'giuong_nem'],
                'trangThaiPhong' => 'DANG_THUE',
                'ghiChu' => 'Gác lửng đúc bê tông cao 2.1m đứng thẳng người, cầu thang ốp gỗ.',
            ],
            [
                'soPhong' => 'P103',
                'maLoaiPhong' => 1, // Đơn tiêu chuẩn
                'tang' => 'Tầng 1',
                'dienTich' => 16.00,
                'soNguoiToiDa' => 1,
                'donGiaMacDinh' => 2200000,
                'tienCocMacDinh' => 2200000,
                'tienNghi' => ['nong_lanh', 'giuong_nem', 'tu_quan_ao'],
                'trangThaiPhong' => 'TRONG',
                'ghiChu' => 'Phòng nhỏ gọn cạnh nhà để xe, đi lại nhanh chóng tiện lợi.',
            ],
            [
                'soPhong' => 'P104',
                'maLoaiPhong' => 3, // Gia đình / Nhóm
                'tang' => 'Tầng 1',
                'dienTich' => 36.00,
                'soNguoiToiDa' => 4,
                'donGiaMacDinh' => 5200000,
                'tienCocMacDinh' => 5200000,
                'tienNghi' => ['dieu_hoa', 'nong_lanh', 'tu_lanh', 'ke_bep', 'ban_ghe', 'giuong_nem', 'tu_quan_ao'],
                'trangThaiPhong' => 'DANG_THUE',
                'ghiChu' => 'Căn hộ 2 gian rộng rãi, thích hợp gia đình nhỏ có con.',
            ],

            // --- TẦNG 2 ---
            [
                'soPhong' => 'P201',
                'maLoaiPhong' => 4, // Studio
                'tang' => 'Tầng 2',
                'dienTich' => 26.00,
                'soNguoiToiDa' => 2,
                'donGiaMacDinh' => 3900000,
                'tienCocMacDinh' => 3900000,
                'tienNghi' => ['dieu_hoa', 'nong_lanh', 'tu_lanh', 'ke_bep', 'giuong_nem', 'ban_cong'],
                'trangThaiPhong' => 'DANG_THUE',
                'ghiChu' => 'Ban công hướng Đông đón gió và ánh nắng sớm, phơi đồ riêng.',
            ],
            [
                'soPhong' => 'P202',
                'maLoaiPhong' => 2, // Gác lửng
                'tang' => 'Tầng 2',
                'dienTich' => 24.00,
                'soNguoiToiDa' => 2,
                'donGiaMacDinh' => 3300000,
                'tienCocMacDinh' => 3300000,
                'tienNghi' => ['dieu_hoa', 'nong_lanh', 'gac_lung', 'ke_bep'],
                'trangThaiPhong' => 'TRONG',
                'ghiChu' => 'Vừa sơn mới màu trắng kem, vệ sinh công nghiệp sẵn đón khách.',
            ],
            [
                'soPhong' => 'P203',
                'maLoaiPhong' => 1, // Đơn tiêu chuẩn
                'tang' => 'Tầng 2',
                'dienTich' => 18.00,
                'soNguoiToiDa' => 2,
                'donGiaMacDinh' => 2500000,
                'tienCocMacDinh' => 2500000,
                'tienNghi' => ['dieu_hoa', 'nong_lanh', 'giuong_nem'],
                'trangThaiPhong' => 'DANG_THUE',
                'ghiChu' => 'Phòng yên tĩnh, cửa sổ cách âm tốt, hợp người làm việc online.',
            ],
            [
                'soPhong' => 'P204',
                'maLoaiPhong' => 1, // Đơn tiêu chuẩn
                'tang' => 'Tầng 2',
                'dienTich' => 17.00,
                'soNguoiToiDa' => 2,
                'donGiaMacDinh' => 2300000,
                'tienCocMacDinh' => 2300000,
                'tienNghi' => ['nong_lanh', 'ke_bep'],
                'trangThaiPhong' => 'DANG_SUA_CHUA',
                'ghiChu' => 'Đang thay gạch men nhà tắm và vòi nước, dự kiến xong ngày 15.',
            ],

            // --- TẦNG 3 ---
            [
                'soPhong' => 'P301',
                'maLoaiPhong' => 4, // Studio
                'tang' => 'Tầng 3',
                'dienTich' => 30.00,
                'soNguoiToiDa' => 2,
                'donGiaMacDinh' => 4500000,
                'tienCocMacDinh' => 4500000,
                'tienNghi' => ['dieu_hoa', 'nong_lanh', 'may_giat', 'tu_lanh', 'ke_bep', 'giuong_nem', 'ban_ghe'],
                'trangThaiPhong' => 'DANG_THUE',
                'ghiChu' => 'Căn áp mái view sân thượng lộng gió, trang bị máy giặt riêng.',
            ],
            [
                'soPhong' => 'P302',
                'maLoaiPhong' => 2, // Gác lửng
                'tang' => 'Tầng 3',
                'dienTich' => 25.00,
                'soNguoiToiDa' => 3,
                'donGiaMacDinh' => 3100000,
                'tienCocMacDinh' => 3100000,
                'tienNghi' => ['dieu_hoa', 'nong_lanh', 'gac_lung', 'ke_bep', 'giuong_nem'],
                'trangThaiPhong' => 'TRONG',
                'ghiChu' => 'Cửa thông ra sân phơi chung rộng rãi trên tầng thượng.',
            ],
            [
                'soPhong' => 'P303',
                'maLoaiPhong' => 1, // Đơn tiêu chuẩn
                'tang' => 'Tầng 3',
                'dienTich' => 16.00,
                'soNguoiToiDa' => 1,
                'donGiaMacDinh' => 2300000,
                'tienCocMacDinh' => 2300000,
                'tienNghi' => ['dieu_hoa', 'nong_lanh', 'giuong_nem'],
                'trangThaiPhong' => 'TRONG',
                'ghiChu' => 'Phòng nhỏ gọn, giá rẻ phù hợp với sinh viên độc thân.',
            ],
        ];

        $phongMap = [];
        foreach ($danhSachPhongData as $pData) {
            $phong = PhongTro::create(array_merge($pData, ['dangHoatDong' => 1]));
            $phongMap[$pData['soPhong']] = $phong;
        }

        // 3. TẠO 12 KHÁCH THUÊ THỰC TẾ
        $danhSachKhachData = [
            // Khách thuê 1: Đại diện P101
            [
                'email' => 'nguyenvana@example.com',
                'hoTen' => 'Nguyễn Văn A',
                'ngaySinh' => '1998-05-15',
                'gioiTinh' => 'NAM',
                'soCCCD' => '001098012345',
                'ngayCapCCCD' => '2021-08-10',
                'noiCapCCCD' => 'Cục CSQLHC về TTXH',
                'soDienThoai' => '0912345678',
                'diaChi' => 'Phường Dịch Vọng Hậu, Quận Cầu Giấy, Hà Nội',
                'ngheNghiep' => 'Kỹ sư phần mềm (FPT)',
                'bienSoXe' => '29-X1 888.88',
                'nguoiLienHeKhanCap' => 'Nguyễn Văn Hùng (Bố)',
                'sdtKhanCap' => '0912111222',
                'quanHeKhanCap' => 'Bố ruột',
                'daDangKyTamTru' => 1,
            ],
            // Khách thuê 2: Người ở cùng P101
            [
                'email' => null,
                'hoTen' => 'Lê Quốc Bảo',
                'ngaySinh' => '1999-08-20',
                'gioiTinh' => 'NAM',
                'soCCCD' => '001099023456',
                'ngayCapCCCD' => '2021-10-15',
                'noiCapCCCD' => 'Cục CSQLHC về TTXH',
                'soDienThoai' => '0913456789',
                'diaChi' => 'Phường Ô Chợ Dừa, Quận Đống Đa, Hà Nội',
                'ngheNghiep' => 'Thiết kế đồ họa (Designer)',
                'bienSoXe' => '29-E1 678.90',
                'nguoiLienHeKhanCap' => 'Lê Hoàng Minh (Anh)',
                'sdtKhanCap' => '0913222333',
                'quanHeKhanCap' => 'Anh trai',
                'daDangKyTamTru' => 1,
            ],
            // Khách thuê 3: Đại diện P102
            [
                'email' => 'tranthimai@gmail.com',
                'hoTen' => 'Trần Thị Mai',
                'ngaySinh' => '2001-11-03',
                'gioiTinh' => 'NU',
                'soCCCD' => '036201011223',
                'ngayCapCCCD' => '2022-03-20',
                'noiCapCCCD' => 'Cục CSQLHC về TTXH',
                'soDienThoai' => '0987654321',
                'diaChi' => 'Phường Lộc Vượng, TP. Nam Định',
                'ngheNghiep' => 'Chuyên viên Marketing',
                'bienSoXe' => '18-B2 543.21',
                'nguoiLienHeKhanCap' => 'Trần Văn Đức (Bố)',
                'sdtKhanCap' => '0987111333',
                'quanHeKhanCap' => 'Bố ruột',
                'daDangKyTamTru' => 1,
            ],
            // Khách thuê 4: Đại diện P104 (Gia đình)
            [
                'email' => 'lehoangnam@gmail.com',
                'hoTen' => 'Lê Hoàng Nam',
                'ngaySinh' => '1992-03-25',
                'gioiTinh' => 'NAM',
                'soCCCD' => '079092004567',
                'ngayCapCCCD' => '2021-05-12',
                'noiCapCCCD' => 'Cục CSQLHC về TTXH',
                'soDienThoai' => '0908112233',
                'diaChi' => 'Phường 7, Quận 3, TP. Hồ Chí Minh',
                'ngheNghiep' => 'Trưởng phòng kinh doanh',
                'bienSoXe' => '59-P1 999.66',
                'nguoiLienHeKhanCap' => 'Lê Quang Liêm (Bố)',
                'sdtKhanCap' => '0908777888',
                'quanHeKhanCap' => 'Bố',
                'daDangKyTamTru' => 1,
            ],
            // Khách thuê 5: Vợ anh Nam, ở cùng P104
            [
                'email' => null,
                'hoTen' => 'Phạm Thúy Nga',
                'ngaySinh' => '1995-09-12',
                'gioiTinh' => 'NU',
                'soCCCD' => '079095006789',
                'ngayCapCCCD' => '2021-06-18',
                'noiCapCCCD' => 'Cục CSQLHC về TTXH',
                'soDienThoai' => '0909445566',
                'diaChi' => 'Phường 2, Quận Tân Bình, TP. Hồ Chí Minh',
                'ngheNghiep' => 'Kế toán tổng hợp',
                'bienSoXe' => '59-V2 123.88',
                'nguoiLienHeKhanCap' => 'Lê Hoàng Nam (Chồng)',
                'sdtKhanCap' => '0908112233',
                'quanHeKhanCap' => 'Chồng',
                'daDangKyTamTru' => 1,
            ],
            // Khách thuê 6: Đại diện P201
            [
                'email' => 'vuthihuong@gmail.com',
                'hoTen' => 'Vũ Thị Hương',
                'ngaySinh' => '2000-07-18',
                'gioiTinh' => 'NU',
                'soCCCD' => '031200008899',
                'ngayCapCCCD' => '2022-01-10',
                'noiCapCCCD' => 'Cục CSQLHC về TTXH',
                'soDienThoai' => '0936123456',
                'diaChi' => 'Phường Cầu Đất, Quận Ngô Quyền, Hải Phòng',
                'ngheNghiep' => 'Nhân viên tuyển dụng HR',
                'bienSoXe' => '15-B1 789.12',
                'nguoiLienHeKhanCap' => 'Vũ Đình Toàn (Bố)',
                'sdtKhanCap' => '0936444555',
                'quanHeKhanCap' => 'Bố ruột',
                'daDangKyTamTru' => 1,
            ],
            // Khách thuê 7: Em họ ở cùng P201
            [
                'email' => null,
                'hoTen' => 'Hoàng Diệu Linh',
                'ngaySinh' => '2002-04-10',
                'gioiTinh' => 'NU',
                'soCCCD' => '031202007788',
                'ngayCapCCCD' => '2022-09-05',
                'noiCapCCCD' => 'Cục CSQLHC về TTXH',
                'soDienThoai' => '0936789012',
                'diaChi' => 'Phường An Dương, Quận Lê Chân, Hải Phòng',
                'ngheNghiep' => 'Sinh viên năm cuối ĐH Ngoại Thương',
                'bienSoXe' => '15-H1 234.56',
                'nguoiLienHeKhanCap' => 'Hoàng Văn Thắng (Bố)',
                'sdtKhanCap' => '0936555666',
                'quanHeKhanCap' => 'Bố ruột',
                'daDangKyTamTru' => 1,
            ],
            // Khách thuê 8: Đại diện P203
            [
                'email' => 'dominhtuan@gmail.com',
                'hoTen' => 'Đỗ Minh Tuấn',
                'ngaySinh' => '1997-12-08',
                'gioiTinh' => 'NAM',
                'soCCCD' => '024097003456',
                'ngayCapCCCD' => '2021-09-14',
                'noiCapCCCD' => 'Cục CSQLHC về TTXH',
                'soDienThoai' => '0978998877',
                'diaChi' => 'Thị trấn Bích Động, Thị xã Việt Yên, Bắc Giang',
                'ngheNghiep' => 'Kỹ thuật viên Viettel Telecom',
                'bienSoXe' => '98-K1 456.78',
                'nguoiLienHeKhanCap' => 'Đỗ Văn Cường (Anh)',
                'sdtKhanCap' => '0978111222',
                'quanHeKhanCap' => 'Anh ruột',
                'daDangKyTamTru' => 1,
            ],
            // Khách thuê 9: Đại diện P301
            [
                'email' => 'buianhkhoa@gmail.com',
                'hoTen' => 'Bùi Anh Khoa',
                'ngaySinh' => '1996-01-22',
                'gioiTinh' => 'NAM',
                'soCCCD' => '040096001234',
                'ngayCapCCCD' => '2021-11-20',
                'noiCapCCCD' => 'Cục CSQLHC về TTXH',
                'soDienThoai' => '0945678123',
                'diaChi' => 'Phường Hưng Dũng, TP. Vinh, Nghệ An',
                'ngheNghiep' => 'Bác sĩ nội trú Bệnh viện Đại học Y',
                'bienSoXe' => '37-B2 678.99',
                'nguoiLienHeKhanCap' => 'Bùi Trọng Nghĩa (Bố)',
                'sdtKhanCap' => '0945333444',
                'quanHeKhanCap' => 'Bố ruột',
                'daDangKyTamTru' => 1,
            ],
            // Khách thuê 10: Khách tự do chưa thuê phòng
            [
                'email' => 'nguyenvanquang@gmail.com',
                'hoTen' => 'Nguyễn Văn Quang',
                'ngaySinh' => '2003-06-30',
                'gioiTinh' => 'NAM',
                'soCCCD' => '019203005544',
                'ngayCapCCCD' => '2023-02-15',
                'noiCapCCCD' => 'Cục CSQLHC về TTXH',
                'soDienThoai' => '0961234888',
                'diaChi' => 'Phường Ba Hàng, TP. Phổ Yên, Thái Nguyên',
                'ngheNghiep' => 'Sinh viên K66 ĐH Bách Khoa',
                'bienSoXe' => '20-B1 567.89',
                'nguoiLienHeKhanCap' => 'Nguyễn Văn Tuấn (Bố)',
                'sdtKhanCap' => '0961999000',
                'quanHeKhanCap' => 'Bố',
                'daDangKyTamTru' => 0,
            ],
            // Khách thuê 11: Khách tự do
            [
                'email' => null,
                'hoTen' => 'Đặng Thu Hằng',
                'ngaySinh' => '2001-08-14',
                'gioiTinh' => 'NU',
                'soCCCD' => '038201004433',
                'ngayCapCCCD' => '2022-07-12',
                'noiCapCCCD' => 'Cục CSQLHC về TTXH',
                'soDienThoai' => '0915678999',
                'diaChi' => 'Phường Ba Đình, TP. Thanh Hóa',
                'ngheNghiep' => 'Dược sĩ Nhà thuốc Long Châu',
                'bienSoXe' => '36-B3 890.12',
                'nguoiLienHeKhanCap' => 'Đặng Xuân Hòa (Bố)',
                'sdtKhanCap' => '0915111222',
                'quanHeKhanCap' => 'Bố ruột',
                'daDangKyTamTru' => 0,
            ],
            // Khách thuê 12: Khách cũ đã từng thuê
            [
                'email' => null,
                'hoTen' => 'Phan Văn Hậu',
                'ngaySinh' => '1994-10-05',
                'gioiTinh' => 'NAM',
                'soCCCD' => '044094008877',
                'ngayCapCCCD' => '2020-12-05',
                'noiCapCCCD' => 'Cục CSQLHC về TTXH',
                'soDienThoai' => '0981223344',
                'diaChi' => 'Phường Đồng Phú, TP. Đồng Hới, Quảng Bình',
                'ngheNghiep' => 'Kiến trúc sư nội thất',
                'bienSoXe' => '73-F1 112.23',
                'nguoiLienHeKhanCap' => 'Phan Văn Hải (Anh)',
                'sdtKhanCap' => '0981888999',
                'quanHeKhanCap' => 'Anh trai',
                'daDangKyTamTru' => 1,
            ],
        ];

        $khachMap = [];
        foreach ($danhSachKhachData as $kData) {
            $maNguoiDung = null;
            if (!empty($kData['email'])) {
                $user = NguoiDung::create([
                    'email' => $kData['email'],
                    'matKhau' => Hash::make('123456'),
                    'vaiTro' => 'KHACH_THUE',
                    'dangHoatDong' => 1,
                ]);
                $maNguoiDung = $user->maNguoiDung;
            }

            unset($kData['email']);
            $kData['maNguoiDung'] = $maNguoiDung;
            $kData['dangHoatDong'] = 1;

            $khach = KhachThue::create($kData);
            $khachMap[$khach->hoTen] = $khach;
        }

        // 4. TẠO CÁC HỢP ĐỒNG ĐANG THUÊ HIỆU LỰC & ĐÃ THANH LÝ
        $danhSachHopDongData = [
            // HĐ 1: Phòng P101
            [
                'soPhong' => 'P101',
                'tenKhach' => 'Nguyễn Văn A',
                'soLuongNguoi' => 2, // An + Bảo
                'ngayBatDau' => '2026-01-01',
                'ngayKetThuc' => '2026-12-31',
                'giaThueChot' => 4200000,
                'tienDatCoc' => 4200000,
                'tienHoanCoc' => null,
                'trangThaiHD' => 'HIEU_LUC',
                'ghiChu' => 'Hợp đồng thuê 1 năm, đóng tiền ngày mùng 5 hàng tháng.',
                'nguoiOCung' => [
                    ['tenKhach' => 'Lê Quốc Bảo', 'ngayVaoO' => '2026-01-01', 'ghiChu' => 'Bạn cùng phòng / Đồng nghiệp'],
                ],
            ],
            // HĐ 2: Phòng P102
            [
                'soPhong' => 'P102',
                'tenKhach' => 'Trần Thị Mai',
                'soLuongNguoi' => 1,
                'ngayBatDau' => '2026-02-15',
                'ngayKetThuc' => '2027-02-14',
                'giaThueChot' => 3200000,
                'tienDatCoc' => 3200000,
                'tienHoanCoc' => null,
                'trangThaiHD' => 'HIEU_LUC',
                'ghiChu' => 'Khách nữ ở 1 mình, giữ gìn phòng rất sạch sẽ.',
                'nguoiOCung' => [],
            ],
            // HĐ 3: Phòng P104 (Gia đình)
            [
                'soPhong' => 'P104',
                'tenKhach' => 'Lê Hoàng Nam',
                'soLuongNguoi' => 2, // Nam + Nga
                'ngayBatDau' => '2025-10-01',
                'ngayKetThuc' => '2026-10-01',
                'giaThueChot' => 5200000,
                'tienDatCoc' => 5200000,
                'tienHoanCoc' => null,
                'trangThaiHD' => 'HIEU_LUC',
                'ghiChu' => 'Hộ gia đình thuê dài hạn.',
                'nguoiOCung' => [
                    ['tenKhach' => 'Phạm Thúy Nga', 'ngayVaoO' => '2025-10-01', 'ghiChu' => 'Vợ của người đại diện'],
                ],
            ],
            // HĐ 4: Phòng P201
            [
                'soPhong' => 'P201',
                'tenKhach' => 'Vũ Thị Hương',
                'soLuongNguoi' => 2, // Hương + Linh
                'ngayBatDau' => '2026-03-01',
                'ngayKetThuc' => '2027-02-28',
                'giaThueChot' => 3900000,
                'tienDatCoc' => 3900000,
                'tienHoanCoc' => null,
                'trangThaiHD' => 'HIEU_LUC',
                'ghiChu' => 'Khách thuê 2 chị em, có xe máy gửi dưới hầm.',
                'nguoiOCung' => [
                    ['tenKhach' => 'Hoàng Diệu Linh', 'ngayVaoO' => '2026-03-01', 'ghiChu' => 'Em họ ở cùng học đại học'],
                ],
            ],
            // HĐ 5: Phòng P203
            [
                'soPhong' => 'P203',
                'tenKhach' => 'Đỗ Minh Tuấn',
                'soLuongNguoi' => 1,
                'ngayBatDau' => '2026-04-01',
                'ngayKetThuc' => '2027-03-31',
                'giaThueChot' => 2500000,
                'tienDatCoc' => 2500000,
                'tienHoanCoc' => null,
                'trangThaiHD' => 'HIEU_LUC',
                'ghiChu' => 'Hợp đồng 1 năm, đóng tiền đúng hạn.',
                'nguoiOCung' => [],
            ],
            // HĐ 6: Phòng P301
            [
                'soPhong' => 'P301',
                'tenKhach' => 'Bùi Anh Khoa',
                'soLuongNguoi' => 1,
                'ngayBatDau' => '2026-05-01',
                'ngayKetThuc' => '2027-04-30',
                'giaThueChot' => 4500000,
                'tienDatCoc' => 4500000,
                'tienHoanCoc' => null,
                'trangThaiHD' => 'HIEU_LUC',
                'ghiChu' => 'Bác sĩ trực viện thường xuyên, phòng dùng nghỉ ngơi.',
                'nguoiOCung' => [],
            ],
            // HĐ 7: Hợp đồng cũ đã thanh lý kết thúc (Phòng P202)
            [
                'soPhong' => 'P202',
                'tenKhach' => 'Phan Văn Hậu',
                'soLuongNguoi' => 1,
                'ngayBatDau' => '2025-01-01',
                'ngayKetThuc' => '2025-12-31',
                'giaThueChot' => 3300000,
                'tienDatCoc' => 3300000,
                'tienHoanCoc' => 3300000,
                'trangThaiHD' => 'DA_KET_THUC',
                'ghiChu' => 'Đã hết hạn hợp đồng, bàn giao phòng nguyên vẹn và hoàn cọc đầy đủ.',
                'nguoiOCung' => [],
            ],
        ];

        foreach ($danhSachHopDongData as $hdData) {
            $phong = $phongMap[$hdData['soPhong']] ?? null;
            $khach = $khachMap[$hdData['tenKhach']] ?? null;

            if (!$phong || !$khach) {
                continue;
            }

            $hopDong = HopDong::create([
                'maPhong' => $phong->maPhong,
                'maKhach' => $khach->maKhach,
                'soLuongNguoi' => $hdData['soLuongNguoi'],
                'ngayBatDau' => $hdData['ngayBatDau'],
                'ngayKetThuc' => $hdData['ngayKetThuc'],
                'giaThueChot' => $hdData['giaThueChot'],
                'tienDatCoc' => $hdData['tienDatCoc'],
                'tienHoanCoc' => $hdData['tienHoanCoc'],
                'trangThaiHD' => $hdData['trangThaiHD'],
                'ghiChu' => $hdData['ghiChu'],
            ]);

            // Thêm người ở cùng nếu có
            foreach ($hdData['nguoiOCung'] as $noc) {
                $khachOCung = $khachMap[$noc['tenKhach']] ?? null;
                if ($khachOCung) {
                    NguoiOCung::create([
                        'maHopDong' => $hopDong->maHopDong,
                        'maKhach' => $khachOCung->maKhach,
                        'ngayVaoO' => $noc['ngayVaoO'],
                        'ghiChu' => $noc['ghiChu'],
                        'dangHoatDong' => 1,
                    ]);
                }
            }

            // Tạo chỉ số điện nước & hóa đơn mẫu cho các hợp đồng đang hiệu lực
            if ($hdData['trangThaiHD'] === 'HIEU_LUC') {
                $thangDauThang = Carbon::now()->startOfMonth()->format('Y-m-d');
                $ngayGhi = Carbon::now()->startOfMonth()->addDays(4)->format('Y-m-d');

                $dienCu = rand(100, 300);
                $dienMoi = $dienCu + rand(80, 180);
                $nuocCu = rand(20, 50);
                $nuocMoi = $nuocCu + rand(6, 15);

                $maChiSo = DB::table('chisodiennuoc')->insertGetId([
                    'maPhong' => $phong->maPhong,
                    'thangGhiNhan' => $thangDauThang,
                    'chiSoDienCu' => $dienCu,
                    'chiSoDienMoi' => $dienMoi,
                    'chiSoNuocCu' => $nuocCu,
                    'chiSoNuocMoi' => $nuocMoi,
                    'ngayGhi' => $ngayGhi,
                ]);

                // Tính tiền điện nước dịch vụ
                $tienPhong = $hdData['giaThueChot'];
                $tienDien = ($dienMoi - $dienCu) * 3500;
                $tienNuoc = ($nuocMoi - $nuocCu) * 25000;
                $tienWifi = 100000;
                $tienRac = 50000;
                $tongTien = $tienPhong + $tienDien + $tienNuoc + $tienWifi + $tienRac;

                // Các phòng P101, P102, P104, P201 đã thanh toán; P203, P301 còn nợ
                $daThanhToan = in_array($hdData['soPhong'], ['P101', 'P102', 'P104', 'P201']);

                $maHoaDon = DB::table('hoadon')->insertGetId([
                    'maHopDong' => $hopDong->maHopDong,
                    'maChiSo' => $maChiSo,
                    'thangNam' => $thangDauThang,
                    'ngayLap' => $ngayGhi,
                    'hanThanhToan' => Carbon::now()->startOfMonth()->addDays(10)->format('Y-m-d'),
                    'tongTien' => $tongTien,
                    'trangThaiThanhToan' => $daThanhToan ? 'DA_THANH_TOAN' : 'CON_NO',
                    'ngayThanhToan' => $daThanhToan ? Carbon::now()->startOfMonth()->addDays(6)->format('Y-m-d') : null,
                    'ghiChu' => 'Tiền phòng và dịch vụ tháng ' . Carbon::now()->format('m/Y'),
                ]);

                // Chi tiết hóa đơn
                DB::table('chitiethoadon')->insert([
                    [
                        'maHoaDon' => $maHoaDon,
                        'maDichVu' => null,
                        'noiDung' => 'Tiền thuê phòng ' . $phong->soPhong,
                        'soLuong' => 1,
                        'donGia' => $tienPhong,
                    ],
                    [
                        'maHoaDon' => $maHoaDon,
                        'maDichVu' => 1, // Điện
                        'noiDung' => 'Tiền điện (' . ($dienMoi - $dienCu) . ' kWh x 3.500đ)',
                        'soLuong' => $dienMoi - $dienCu,
                        'donGia' => 3500,
                    ],
                    [
                        'maHoaDon' => $maHoaDon,
                        'maDichVu' => 2, // Nước
                        'noiDung' => 'Tiền nước (' . ($nuocMoi - $nuocCu) . ' m3 x 25.000đ)',
                        'soLuong' => $nuocMoi - $nuocCu,
                        'donGia' => 25000,
                    ],
                    [
                        'maHoaDon' => $maHoaDon,
                        'maDichVu' => 4, // Wifi
                        'noiDung' => 'Cước Internet Wifi tốc độ cao',
                        'soLuong' => 1,
                        'donGia' => 100000,
                    ],
                    [
                        'maHoaDon' => $maHoaDon,
                        'maDichVu' => 3, // Rác
                        'noiDung' => 'Phí vệ sinh rác sinh hoạt',
                        'soLuong' => 1,
                        'donGia' => 50000,
                    ],
                ]);
            }
        }
    }
}
