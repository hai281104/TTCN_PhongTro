<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hợp Đồng #HD-{{ str_pad($hopDong->maHopDong, 4, '0', STR_PAD_LEFT) }} - Quản Lý Nhà Trọ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/quan-ly-hop-dong.css') }}">
    <script src="{{ asset('js/quan-ly-hop-dong.js') }}" defer></script>
</head>
<body class="h-full font-sans antialiased text-zinc-900 bg-zinc-50">
    <div class="min-h-full">
        <!-- HEADER -->
        <header class="bg-white border-b border-zinc-200 sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                            QL
                        </div>
                        <div>
                            <h1 class="text-base font-semibold text-zinc-900 leading-tight">Quản Lý Nhà Trọ</h1>
                            <p class="text-xs text-zinc-500">Cổng Quản Trị Chủ Trọ</p>
                        </div>
                    </div>

                    <nav class="hidden md:flex items-center gap-2 pl-4 border-l border-zinc-200">
                        <a href="{{ route('tong-quan.chu-tro') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
                            Tổng quan
                        </a>
                        <a href="{{ route('phong-tro.danh-sach') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
                            Quản lý phòng
                        </a>
                        <a href="{{ route('khach-thue.danh-sach') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
                            Khách thuê
                        </a>
                        <a href="{{ route('hop-dong.danh-sach') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-900 text-white">
                            Hợp đồng
                        </a>
                    </nav>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-medium text-zinc-900">{{ Auth::user()->email }}</div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Chủ trọ
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

        <!-- MAIN -->
        <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- THÔNG BÁO -->
            @if (session('thongBaoThanhCong'))
                <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/70 text-emerald-800 text-xs flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('thongBaoThanhCong') }}</span>
                    </div>
                </div>
            @endif

            @if (session('thongBaoLoi') || $errors->any())
                <div class="p-4 rounded-xl border border-red-200 bg-red-50/70 text-red-800 text-xs space-y-1">
                    @if (session('thongBaoLoi'))
                        <div>{{ session('thongBaoLoi') }}</div>
                    @endif
                    @foreach ($errors->all() as $loi)
                        <div>&bull; {{ $loi }}</div>
                    @endforeach
                </div>
            @endif

            <!-- THANH ĐIỀU HƯỚNG QUAY LẠI & HÀNH ĐỘNG -->
            <div class="flex items-center justify-between">
                <a href="{{ route('hop-dong.danh-sach') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-600 hover:text-zinc-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Quay lại danh sách hợp đồng</span>
                </a>

                @if ($hopDong->trangThaiHD === 'HIEU_LUC')
                    <div class="flex items-center gap-2">
                        <button type="button" data-hanh-dong="mo-modal-them-nguoi-o"
                            class="px-3 py-1.5 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors shadow-xs">
                            + Thêm người ở cùng
                        </button>

                        <button type="button"
                            data-hanh-dong="mo-modal-ket-thuc"
                            data-ma-hop-dong="{{ $hopDong->maHopDong }}"
                            data-so-phong="{{ optional($hopDong->phongTro)->soPhong }}"
                            data-tien-coc="{{ $hopDong->tienDatCoc }}"
                            class="px-3.5 py-1.5 rounded-lg bg-red-600 text-white text-xs font-medium hover:bg-red-700 transition-colors shadow-xs">
                            Thanh lý hợp đồng
                        </button>
                    </div>
                @endif
            </div>

            <!-- THẺ TỔNG QUAN HỢP ĐỒNG -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                <div class="flex items-start justify-between pb-4 border-b border-zinc-100 mb-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl font-bold text-zinc-900">
                                Hợp Đồng #HD-{{ str_pad($hopDong->maHopDong, 4, '0', STR_PAD_LEFT) }}
                            </h2>
                            @if ($hopDong->trangThaiHD === 'HIEU_LUC')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Đang hiệu lực
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-zinc-100 text-zinc-600 border border-zinc-200">
                                    Đã kết thúc
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-zinc-500 mt-1">
                            Lập cho phòng: <strong class="text-zinc-900">Phòng {{ optional($hopDong->phongTro)->soPhong }}</strong> &bull;
                            Khách đại diện: <strong class="text-zinc-900">{{ optional($hopDong->khachThue)->hoTen }}</strong>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                    <div>
                        <span class="text-zinc-500 block">Giá thuê chốt mỗi tháng:</span>
                        <strong class="text-zinc-900 text-sm tabular-nums">{{ number_format($hopDong->giaThueChot, 0, ',', '.') }} đ</strong>
                    </div>
                    <div>
                        <span class="text-zinc-500 block">Tiền đặt cọc giữ chân:</span>
                        <strong class="text-emerald-700 text-sm tabular-nums">{{ number_format($hopDong->tienDatCoc, 0, ',', '.') }} đ</strong>
                    </div>
                    <div>
                        <span class="text-zinc-500 block">Ngày bắt đầu tính tiền:</span>
                        <span class="text-zinc-800 font-medium">{{ \Carbon\Carbon::parse($hopDong->ngayBatDau)->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span class="text-zinc-500 block">Ngày kết thúc / trả phòng:</span>
                        <span class="text-zinc-800 font-medium">{{ $hopDong->ngayKetThuc ? \Carbon\Carbon::parse($hopDong->ngayKetThuc)->format('d/m/Y') : '(Chưa có ngày kết thúc)' }}</span>
                    </div>
                </div>

                @if ($hopDong->trangThaiHD === 'DA_KET_THUC')
                    <div class="mt-4 pt-3 border-t border-zinc-100 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-zinc-50 p-3 rounded-lg border border-zinc-200">
                        <div>
                            <span class="text-zinc-500 block">Trạng thái:</span>
                            <span class="font-medium text-zinc-700">Hợp đồng đã hoàn tất thanh lý</span>
                        </div>
                        <div>
                            <span class="text-zinc-500 block">Tiền cọc đã hoàn lại:</span>
                            <strong class="text-zinc-900 tabular-nums">{{ number_format($hopDong->tienHoanCoc ?? 0, 0, ',', '.') }} đ</strong>
                        </div>
                    </div>
                @endif
            </div>

            <!-- 2 CỘT: PHÒNG & KHÁCH THUÊ -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- THÔNG TIN PHÒNG -->
                <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                        <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider">Thông tin phòng trọ</h3>
                        @if ($hopDong->phongTro)
                            <a href="{{ route('phong-tro.chi-tiet', $hopDong->maPhong) }}" class="text-xs text-sky-700 hover:underline">
                                Xem chi tiết phòng &rarr;
                            </a>
                        @endif
                    </div>

                    @if ($hopDong->phongTro)
                        <div class="space-y-3 text-xs">
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Số phòng:</span>
                                <strong class="text-zinc-900 font-semibold">Phòng {{ $hopDong->phongTro->soPhong }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Đơn giá niêm yết:</span>
                                <span class="tabular-nums font-medium text-zinc-800">{{ number_format($hopDong->phongTro->donGiaMacDinh, 0, ',', '.') }} đ</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Trạng thái phòng hiện tại:</span>
                                <span class="font-medium">{{ $hopDong->phongTro->trangThaiPhong }}</span>
                            </div>
                            @if ($hopDong->phongTro->ghiChu)
                                <div class="pt-2 border-t border-zinc-100">
                                    <span class="text-zinc-500 block mb-1">Ghi chú đặc điểm:</span>
                                    <p class="p-2.5 bg-zinc-50 rounded border border-zinc-200 text-zinc-700">{{ $hopDong->phongTro->ghiChu }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-xs text-zinc-500">Thông tin phòng không tồn tại.</p>
                    @endif
                </div>

                <!-- THÔNG TIN KHÁCH ĐẠI DIỆN -->
                <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                        <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider">Khách thuê đại diện</h3>
                        @if ($hopDong->khachThue)
                            <a href="{{ route('khach-thue.chi-tiet', $hopDong->maKhach) }}" class="text-xs text-sky-700 hover:underline">
                                Xem hồ sơ khách &rarr;
                            </a>
                        @endif
                    </div>

                    @if ($hopDong->khachThue)
                        <div class="space-y-3 text-xs">
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Họ và tên:</span>
                                <strong class="text-zinc-900 font-semibold">{{ $hopDong->khachThue->hoTen }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Số CCCD:</span>
                                <span class="font-medium text-zinc-800">{{ $hopDong->khachThue->soCCCD }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Số điện thoại:</span>
                                <span class="font-medium text-zinc-800">{{ $hopDong->khachThue->soDienThoai }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Địa chỉ thường trú:</span>
                                <span class="text-zinc-700 text-right">{{ $hopDong->khachThue->diaChi ?: 'Chưa cập nhật' }}</span>
                            </div>
                            @if ($hopDong->khachThue->nguoiDung)
                                <div class="pt-2 border-t border-zinc-100 flex justify-between">
                                    <span class="text-zinc-500">Tài khoản đăng nhập:</span>
                                    <span class="text-zinc-800">{{ $hopDong->khachThue->nguoiDung->email }}</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-xs text-zinc-500">Hồ sơ khách thuê không tồn tại.</p>
                    @endif
                </div>
            </div>

            <!-- DANH SÁCH NGƯỜI Ở CÙNG -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider">Danh sách người ở cùng trong phòng</h3>
                        <p class="text-xs text-zinc-500 mt-0.5">Nhân khẩu cùng cư trú ngoài người đại diện ký hợp đồng.</p>
                    </div>

                    @if ($hopDong->trangThaiHD === 'HIEU_LUC')
                        <button type="button" data-hanh-dong="mo-modal-them-nguoi-o"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors shadow-xs">
                            + Thêm người ở cùng
                        </button>
                    @endif
                </div>

                @if ($hopDong->nguoiOCung && $hopDong->nguoiOCung->count() > 0)
                    <div class="overflow-x-auto rounded-lg border border-zinc-200">
                        <table class="min-w-full divide-y divide-zinc-200 text-xs">
                            <thead class="bg-zinc-50 text-zinc-600 font-medium text-left">
                                <tr>
                                    <th scope="col" class="px-4 py-2.5">Họ và tên</th>
                                    <th scope="col" class="px-4 py-2.5">Mối quan hệ</th>
                                    <th scope="col" class="px-4 py-2.5">Số CCCD</th>
                                    <th scope="col" class="px-4 py-2.5">Số điện thoại</th>
                                    @if ($hopDong->trangThaiHD === 'HIEU_LUC')
                                        <th scope="col" class="px-4 py-2.5 text-right">Thao tác</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 bg-white">
                                @foreach ($hopDong->nguoiOCung as $nguoi)
                                    <tr class="hover:bg-zinc-50 transition-colors">
                                        <td class="px-4 py-2.5 font-medium text-zinc-900">{{ $nguoi->hoTen }}</td>
                                        <td class="px-4 py-2.5 text-zinc-600">{{ $nguoi->quanHe ?: 'Ở cùng' }}</td>
                                        <td class="px-4 py-2.5 text-zinc-600">{{ $nguoi->soCCCD ?: '—' }}</td>
                                        <td class="px-4 py-2.5 text-zinc-600 tabular-nums">{{ $nguoi->soDienThoai ?: '—' }}</td>
                                        @if ($hopDong->trangThaiHD === 'HIEU_LUC')
                                            <td class="px-4 py-2.5 text-right">
                                                <form action="{{ route('hop-dong.xoa-nguoi-o-cung', [$hopDong->maHopDong, $nguoi->maNguoiO]) }}" method="POST"
                                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa người ở cùng này khỏi hợp đồng?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">
                                                        Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-xs text-zinc-500 bg-zinc-50 rounded-lg border border-zinc-200">
                        Chưa có người ở cùng nào được thêm vào hợp đồng này.
                    </div>
                @endif
            </div>

            <!-- LỊCH SỬ HÓA ĐƠN CỦA HỢP ĐỒNG -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider">Hóa đơn phát sinh từ hợp đồng</h3>
                        <p class="text-xs text-zinc-500 mt-0.5">Danh sách các hóa đơn tiền phòng và dịch vụ hàng tháng.</p>
                    </div>
                </div>

                @if ($hopDong->hoaDon && $hopDong->hoaDon->count() > 0)
                    <div class="overflow-x-auto rounded-lg border border-zinc-200">
                        <table class="min-w-full divide-y divide-zinc-200 text-xs">
                            <thead class="bg-zinc-50 text-zinc-600 font-medium text-left">
                                <tr>
                                    <th scope="col" class="px-4 py-2.5">Kỳ thu tiền</th>
                                    <th scope="col" class="px-4 py-2.5">Ngày lập</th>
                                    <th scope="col" class="px-4 py-2.5">Hạn nộp</th>
                                    <th scope="col" class="px-4 py-2.5 text-right">Tổng tiền</th>
                                    <th scope="col" class="px-4 py-2.5 text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 bg-white">
                                @foreach ($hopDong->hoaDon as $hd)
                                    <tr class="hover:bg-zinc-50 transition-colors">
                                        <td class="px-4 py-2.5 font-semibold text-zinc-900">
                                            Tháng {{ \Carbon\Carbon::parse($hd->thangNam)->format('m/Y') }}
                                        </td>
                                        <td class="px-4 py-2.5 text-zinc-600">{{ \Carbon\Carbon::parse($hd->ngayLap)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2.5 text-zinc-600">{{ $hd->hanThanhToan ? \Carbon\Carbon::parse($hd->hanThanhToan)->format('d/m/Y') : '—' }}</td>
                                        <td class="px-4 py-2.5 text-right font-bold text-zinc-900 tabular-nums">
                                            {{ number_format($hd->tongTien, 0, ',', '.') }} đ
                                        </td>
                                        <td class="px-4 py-2.5 text-center">
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-xs text-zinc-500 bg-zinc-50 rounded-lg border border-zinc-200">
                        Chưa phát sinh hóa đơn nào cho hợp đồng này.
                    </div>
                @endif
            </div>

        </main>
    </div>

    <!-- MODAL THÊM NGƯỜI Ở CÙNG -->
    <div id="modalThemNguoiO" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <h3 class="text-sm font-semibold text-zinc-900">Thêm Người Ở Cùng Phòng</h3>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-lg">&times;</button>
            </div>

            <form action="{{ route('hop-dong.them-nguoi-o-cung', $hopDong->maHopDong) }}" method="POST">
                @csrf
                <div class="space-y-4 text-xs">
                    <div>
                        <label for="hoTenNguoiO" class="block font-medium text-zinc-700 mb-1">
                            Họ và tên <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="hoTen" id="hoTenNguoiO" required placeholder="Nguyễn Văn B"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>

                    <div>
                        <label for="quanHeNguoiO" class="block font-medium text-zinc-700 mb-1">Mối quan hệ</label>
                        <input type="text" name="quanHe" id="quanHeNguoiO" placeholder="Vợ/Chồng, Con, Bạn bè..."
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>

                    <div>
                        <label for="soCCCDNguoiO" class="block font-medium text-zinc-700 mb-1">Số CCCD (12 chữ số)</label>
                        <input type="text" name="soCCCD" id="soCCCDNguoiO" maxlength="12" placeholder="079..."
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>

                    <div>
                        <label for="soDienThoaiNguoiO" class="block font-medium text-zinc-700 mb-1">Số điện thoại liên hệ</label>
                        <input type="text" name="soDienThoai" id="soDienThoaiNguoiO" placeholder="09..."
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2 pt-4 border-t border-zinc-100">
                    <button type="button" data-dong-modal
                        class="px-3.5 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                        Thêm người ở
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL THANH LÝ / KẾT THÚC HỢP ĐỒNG -->
    <div id="modalKetThucHopDong" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <h3 class="text-sm font-semibold text-zinc-900">Thanh Lý / Kết Thúc Hợp Đồng</h3>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-lg">&times;</button>
            </div>

            <p class="text-xs text-zinc-600 mb-4">
                Bạn đang thực hiện kết thúc hợp đồng thuê <strong class="text-zinc-900">Phòng {{ optional($hopDong->phongTro)->soPhong }}</strong>. Phòng sẽ tự động chuyển về trạng thái <strong>"Trống"</strong>.
            </p>

            <form id="bieuMauKetThucHopDong" action="{{ route('hop-dong.ket-thuc', $hopDong->maHopDong) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-4 text-xs">
                    <div>
                        <label for="inputNgayKetThuc" class="block font-medium text-zinc-700 mb-1">
                            Ngày trả phòng / Kết thúc hợp đồng <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="ngayKetThuc" id="inputNgayKetThuc" required value="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>

                    <div>
                        <label for="inputTienHoanCoc" class="block font-medium text-zinc-700 mb-1">
                            Số tiền cọc hoàn trả lại cho khách (đ)
                        </label>
                        <input type="text" inputmode="numeric" name="tienHoanCoc" id="inputTienHoanCoc" placeholder="0" value="{{ number_format($hopDong->tienDatCoc, 0, ',', '.') }}"
                            class="dinh-dang-tien w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2 pt-4 border-t border-zinc-100">
                    <button type="button" data-dong-modal
                        class="px-3.5 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white text-xs font-medium hover:bg-red-700 transition-colors shadow-xs">
                        Xác nhận thanh lý
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
