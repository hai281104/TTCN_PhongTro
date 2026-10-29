<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng Khách Thuê - Quản Lý Nhà Trọ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/tong-quan.css') }}">
    <script src="{{ asset('js/tong-quan.js') }}" defer></script>
</head>
<body class="h-full font-sans antialiased text-zinc-900 bg-zinc-50">
    <div class="min-h-full">
        <!-- HEADER -->
        <header class="bg-white border-b border-zinc-200 sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                        KT
                    </div>
                    <div>
                        <h1 class="text-base font-semibold text-zinc-900 leading-tight">Quản Lý Nhà Trọ</h1>
                        <p class="text-xs text-zinc-500">Cổng Dành Cho Khách Thuê</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-medium text-zinc-900">
                            {{ $khachThue ? $khachThue->hoTen : Auth::user()->email }}
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-sky-50 text-sky-700 border border-sky-200">
                            Khách thuê
                        </span>
                    </div>

                    <form action="{{ route('dang-xuat') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-zinc-300 bg-white hover:bg-zinc-100 text-zinc-700 text-xs font-medium transition-colors shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- NỘI DUNG CHÍNH -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- THẺ CHÀO MỪNG & TỔNG QUAN NHANH -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900">
                        Xin chào, {{ $khachThue ? $khachThue->hoTen : Auth::user()->email }}!
                    </h2>
                    <p class="text-xs text-zinc-600 mt-1">
                        Chào mừng bạn đến với cổng thông tin cá nhân dành riêng cho khách thuê trọ.
                    </p>
                </div>

                @if ($phongTro)
                    <div class="flex items-center gap-2 self-start md:self-auto">
                        <span class="text-xs text-zinc-500 font-medium">Trạng thái phòng:</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">
                            Phòng {{ $phongTro->soPhong }} &bull; Đang thuê
                        </span>
                    </div>
                @endif
            </div>

            @if ($khachThue && $hopDong && $phongTro)
                <!-- 4 THẺ CHỈ SỐ NHANH -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Phòng đang thuê -->
                    <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                        <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Phòng đang thuê</div>
                        <div class="text-2xl font-bold text-zinc-900 mt-2">
                            Phòng {{ $phongTro->soPhong }}
                        </div>
                        <div class="text-xs text-zinc-500 mt-1">
                            Bắt đầu: {{ \Carbon\Carbon::parse($hopDong->ngayBatDau)->format('d/m/Y') }}
                        </div>
                    </div>

                    <!-- Tiền thuê chốt -->
                    <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                        <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Giá thuê phòng</div>
                        <div class="text-2xl font-bold text-zinc-900 mt-2 tabular-nums">
                            {{ number_format($hopDong->giaThueChot, 0, ',', '.') }} đ
                        </div>
                        <div class="text-xs text-zinc-500 mt-1">Mỗi tháng</div>
                    </div>

                    <!-- Tiền đặt cọc -->
                    <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                        <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Tiền cọc giữ chỗ</div>
                        <div class="text-2xl font-bold text-emerald-700 mt-2 tabular-nums">
                            {{ number_format($hopDong->tienDatCoc, 0, ',', '.') }} đ
                        </div>
                        <div class="text-xs text-zinc-500 mt-1">Đã bàn giao cho chủ trọ</div>
                    </div>

                    <!-- Công nợ / Trạng thái hóa đơn -->
                    <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                        <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Hóa đơn tháng này</div>
                        @if ($hoaDonChuaThanhToan)
                            <div class="text-2xl font-bold text-red-600 mt-2 tabular-nums">
                                {{ number_format($hoaDonChuaThanhToan->tongTien, 0, ',', '.') }} đ
                            </div>
                            <div class="text-xs text-red-600 mt-1 font-medium">
                                Hạn nộp: {{ $hoaDonChuaThanhToan->hanThanhToan ? \Carbon\Carbon::parse($hoaDonChuaThanhToan->hanThanhToan)->format('d/m/Y') : 'Chưa có' }}
                            </div>
                        @else
                            <div class="text-2xl font-bold text-emerald-600 mt-2">
                                0 đ
                            </div>
                            <div class="text-xs text-emerald-600 mt-1 font-medium">
                                Đã hoàn tất mọi khoản phí
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 2 CỘT: CHI TIẾT HỢP ĐỒNG & HỒ SƠ KHÁCH THUÊ -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- CỘT 1: HỢP ĐỒNG & ĐIỆN NƯỚC -->
                    <div class="space-y-6">

                        <!-- THÔNG TIN HỢP ĐỒNG -->
                        <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                                <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider">Thông tin hợp đồng thuê</h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Đang có hiệu lực
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <span class="text-zinc-500 block">Mã hợp đồng:</span>
                                    <strong class="text-zinc-900 font-medium">#HD-{{ str_pad($hopDong->maHopDong, 4, '0', STR_PAD_LEFT) }}</strong>
                                </div>
                                <div>
                                    <span class="text-zinc-500 block">Số phòng thuê:</span>
                                    <strong class="text-zinc-900 font-medium">Phòng {{ $phongTro->soPhong }}</strong>
                                </div>
                                <div>
                                    <span class="text-zinc-500 block">Ngày bắt đầu thuê:</span>
                                    <span class="text-zinc-800 font-medium">{{ \Carbon\Carbon::parse($hopDong->ngayBatDau)->format('d/m/Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-zinc-500 block">Ngày hết hạn dự kiến:</span>
                                    <span class="text-zinc-800 font-medium">{{ $hopDong->ngayKetThuc ? \Carbon\Carbon::parse($hopDong->ngayKetThuc)->format('d/m/Y') : 'Không thời hạn' }}</span>
                                </div>
                                <div>
                                    <span class="text-zinc-500 block">Giá thuê chốt mỗi tháng:</span>
                                    <strong class="text-zinc-900 tabular-nums">{{ number_format($hopDong->giaThueChot, 0, ',', '.') }} đ</strong>
                                </div>
                                <div>
                                    <span class="text-zinc-500 block">Tiền đặt cọc:</span>
                                    <strong class="text-zinc-900 tabular-nums">{{ number_format($hopDong->tienDatCoc, 0, ',', '.') }} đ</strong>
                                </div>
                            </div>

                            @if ($phongTro->ghiChu)
                                <div class="mt-4 pt-3 border-t border-zinc-100 text-xs">
                                    <span class="text-zinc-500 block mb-1">Ghi chú phòng trọ:</span>
                                    <p class="p-2.5 bg-zinc-50 rounded-lg border border-zinc-200 text-zinc-700">{{ $phongTro->ghiChu }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- CHỈ SỐ ĐIỆN NƯỚC GẦN NHẤT -->
                        <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                            <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider mb-4">Chỉ số điện nước gần nhất</h3>

                            @if ($chiSoDienNuocMoiNhat)
                                <div class="p-4 bg-zinc-50 rounded-lg border border-zinc-200 space-y-3">
                                    <div class="text-xs text-zinc-500">
                                        Tháng ghi nhận: <strong class="text-zinc-900">{{ \Carbon\Carbon::parse($chiSoDienNuocMoiNhat->thangGhiNhan)->format('m/Y') }}</strong>
                                        (Ghi ngày {{ \Carbon\Carbon::parse($chiSoDienNuocMoiNhat->ngayGhi)->format('d/m/Y') }})
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 pt-2 border-t border-zinc-200">
                                        <!-- Điện -->
                                        <div>
                                            <span class="text-xs font-medium text-zinc-700 block mb-1">Điện sinh hoạt</span>
                                            <div class="text-xs text-zinc-600 space-y-0.5">
                                                <div>Số cũ: <span class="tabular-nums font-medium text-zinc-900">{{ $chiSoDienNuocMoiNhat->chiSoDienCu }}</span></div>
                                                <div>Số mới: <span class="tabular-nums font-medium text-zinc-900">{{ $chiSoDienNuocMoiNhat->chiSoDienMoi }}</span></div>
                                                <div class="pt-1 text-sky-700 font-semibold">
                                                    Tiêu thụ: {{ $chiSoDienNuocMoiNhat->soDienTieuThu }} kWh
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Nước -->
                                        <div>
                                            <span class="text-xs font-medium text-zinc-700 block mb-1">Nước sinh hoạt</span>
                                            <div class="text-xs text-zinc-600 space-y-0.5">
                                                <div>Số cũ: <span class="tabular-nums font-medium text-zinc-900">{{ $chiSoDienNuocMoiNhat->chiSoNuocCu }}</span></div>
                                                <div>Số mới: <span class="tabular-nums font-medium text-zinc-900">{{ $chiSoDienNuocMoiNhat->chiSoNuocMoi }}</span></div>
                                                <div class="pt-1 text-sky-700 font-semibold">
                                                    Tiêu thụ: {{ $chiSoDienNuocMoiNhat->soNuocTieuThu }} m³
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-xs text-zinc-500 p-4 bg-zinc-50 rounded-lg border border-zinc-200 text-center">
                                    Chưa có bản ghi chỉ số điện nước nào trong hệ thống.
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- CỘT 2: HỒ SƠ KHÁCH & NGƯỜI Ở CÙNG -->
                    <div class="space-y-6">

                        <!-- THÔNG TIN HỒ SƠ ĐẠI DIỆN -->
                        <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                            <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider mb-4 pb-3 border-b border-zinc-100">
                                Thông tin người đại diện hợp đồng
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <span class="text-zinc-500 block">Họ và tên:</span>
                                    <strong class="text-zinc-900 text-sm font-semibold">{{ $khachThue->hoTen }}</strong>
                                </div>
                                <div>
                                    <span class="text-zinc-500 block">Số CCCD / CMND:</span>
                                    <span class="text-zinc-800 font-medium">{{ $khachThue->soCCCD }}</span>
                                </div>
                                <div>
                                    <span class="text-zinc-500 block">Số điện thoại:</span>
                                    <span class="text-zinc-800 font-medium">{{ $khachThue->soDienThoai }}</span>
                                </div>
                                <div>
                                    <span class="text-zinc-500 block">Email tài khoản:</span>
                                    <span class="text-zinc-800">{{ Auth::user()->email }}</span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="text-zinc-500 block">Địa chỉ thường trú:</span>
                                    <span class="text-zinc-800">{{ $khachThue->diaChi ?: 'Chưa cập nhật' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- NGƯỜI Ở CÙNG -->
                        <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                                <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider">Người ở cùng trong phòng</h3>
                                <span class="text-xs text-zinc-500 font-medium">{{ $danhSachNguoiOCung->count() }} người</span>
                            </div>

                            @if ($danhSachNguoiOCung->count() > 0)
                                <div class="overflow-x-auto rounded-lg border border-zinc-200">
                                    <table class="min-w-full divide-y divide-zinc-200 text-xs">
                                        <thead class="bg-zinc-50 text-zinc-600 font-medium">
                                            <tr>
                                                <th scope="col" class="px-3.5 py-2.5 text-left">Họ và tên</th>
                                                <th scope="col" class="px-3.5 py-2.5 text-left">Quan hệ</th>
                                                <th scope="col" class="px-3.5 py-2.5 text-right">Số điện thoại</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-zinc-200 bg-white">
                                            @foreach ($danhSachNguoiOCung as $nguoi)
                                                <tr class="hover:bg-zinc-50 transition-colors">
                                                    <td class="px-3.5 py-2.5 font-medium text-zinc-900">{{ $nguoi->hoTen }}</td>
                                                    <td class="px-3.5 py-2.5 text-zinc-600">{{ $nguoi->quanHe ?: 'Ở cùng' }}</td>
                                                    <td class="px-3.5 py-2.5 text-right tabular-nums text-zinc-700">{{ $nguoi->soDienThoai ?: '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-xs text-zinc-500 p-4 bg-zinc-50 rounded-lg border border-zinc-200 text-center">
                                    Hiện chưa có người ở cùng nào được đăng ký trong hợp đồng này.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- BẢNG LỊCH SỬ HÓA ĐƠN & CHI TIẾT CÁC KHOẢN PHÍ -->
                <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                        <div>
                            <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider">Lịch sử hóa đơn tiền phòng</h3>
                            <p class="text-xs text-zinc-500 mt-0.5">Danh sách hóa đơn các tháng và chi tiết các khoản thu.</p>
                        </div>
                    </div>

                    @if ($danhSachHoaDon->count() > 0)
                        <div class="overflow-x-auto rounded-lg border border-zinc-200">
                            <table class="min-w-full divide-y divide-zinc-200 text-xs">
                                <thead class="bg-zinc-50 text-zinc-600 font-medium text-left">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Kỳ thu tiền</th>
                                        <th scope="col" class="px-4 py-3">Ngày lập</th>
                                        <th scope="col" class="px-4 py-3">Hạn thanh toán</th>
                                        <th scope="col" class="px-4 py-3 text-right">Tổng tiền</th>
                                        <th scope="col" class="px-4 py-3 text-center">Trạng thái</th>
                                        <th scope="col" class="px-4 py-3 text-right">Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-200 bg-white">
                                    @foreach ($danhSachHoaDon as $hd)
                                        <tr class="hover:bg-zinc-50/70 transition-colors">
                                            <td class="px-4 py-3 font-semibold text-zinc-900">
                                                Tháng {{ \Carbon\Carbon::parse($hd->thangNam)->format('m/Y') }}
                                            </td>
                                            <td class="px-4 py-3 text-zinc-600">
                                                {{ \Carbon\Carbon::parse($hd->ngayLap)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-3 text-zinc-600">
                                                {{ $hd->hanThanhToan ? \Carbon\Carbon::parse($hd->hanThanhToan)->format('d/m/Y') : '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-bold text-zinc-900 tabular-nums">
                                                {{ number_format($hd->tongTien, 0, ',', '.') }} đ
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($hd->trangThaiThanhToan === 'DA_THANH_TOAN')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        Đã thanh toán
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-red-50 text-red-700 border border-red-200">
                                                        Còn nợ
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <button type="button"
                                                    data-hanh-dong="xem-chi-tiet-hoa-don"
                                                    data-ma-hoa-don="{{ $hd->maHoaDon }}"
                                                    class="px-2.5 py-1 rounded border border-zinc-300 text-zinc-700 text-[11px] font-medium hover:bg-zinc-100 transition-colors">
                                                    Xem chi tiết
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- HÀNG CHI TIẾT TỪNG KHOẢN PHÍ (BẬT/TẮT) -->
                                        <tr id="chiTietHoaDon-{{ $hd->maHoaDon }}" class="hidden bg-zinc-50 chuyen-dong-chi-tiet">
                                            <td colspan="6" class="px-4 py-3">
                                                <div class="rounded-lg border border-zinc-200 bg-white p-3 space-y-2">
                                                    <div class="text-xs font-semibold text-zinc-800">
                                                        Các khoản tính tiền trong hóa đơn tháng {{ \Carbon\Carbon::parse($hd->thangNam)->format('m/Y') }}:
                                                    </div>

                                                    <table class="min-w-full divide-y divide-zinc-100 text-xs">
                                                        <thead class="text-zinc-500 font-medium text-left">
                                                            <tr>
                                                                <th class="py-1">Nội dung</th>
                                                                <th class="py-1 text-right">Số lượng</th>
                                                                <th class="py-1 text-right">Đơn giá</th>
                                                                <th class="py-1 text-right">Thành tiền</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-zinc-100">
                                                            @foreach ($hd->chiTietHoaDon as $ct)
                                                                <tr>
                                                                    <td class="py-1.5 text-zinc-800 font-medium">{{ $ct->noiDung }}</td>
                                                                    <td class="py-1.5 text-right tabular-nums text-zinc-600">{{ rtrim(rtrim($ct->soLuong, '0'), '.') }}</td>
                                                                    <td class="py-1.5 text-right tabular-nums text-zinc-600">{{ number_format($ct->donGia, 0, ',', '.') }} đ</td>
                                                                    <td class="py-1.5 text-right tabular-nums font-semibold text-zinc-900">{{ number_format($ct->thanhTien, 0, ',', '.') }} đ</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="border-t border-zinc-200 font-bold text-zinc-900">
                                                                <td colspan="3" class="pt-2 text-right">Tổng cộng thanh toán:</td>
                                                                <td class="pt-2 text-right tabular-nums">{{ number_format($hd->tongTien, 0, ',', '.') }} đ</td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-6 text-center text-xs text-zinc-500 bg-zinc-50 rounded-lg border border-zinc-200">
                            Hiện chưa có hóa đơn nào được phát hành cho hợp đồng của bạn.
                        </div>
                    @endif
                </div>

            @else
                <!-- TRƯỜNG HỢP CHƯA CÓ HỢP ĐỒNG HOẶC HỒ SƠ -->
                <div class="bg-white border border-zinc-200 rounded-xl p-8 shadow-xs text-center max-w-xl mx-auto space-y-3">
                    <div class="w-12 h-12 rounded-full bg-zinc-100 text-zinc-600 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-zinc-900">Chưa có hợp đồng thuê phòng hiệu lực</h3>
                    <p class="text-xs text-zinc-600 leading-relaxed">
                        Tài khoản <strong class="text-zinc-900">{{ Auth::user()->email }}</strong> của bạn hiện chưa được liên kết với một hợp đồng phòng trọ đang có hiệu lực trong hệ thống.
                    </p>
                    <p class="text-xs text-zinc-500">
                        Vui lòng liên hệ với chủ trọ để được kiểm tra và kích hoạt thông tin phòng trọ của bạn.
                    </p>
                </div>
            @endif

        </main>
    </div>
</body>
</html>
