<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Phòng {{ $phongTro->soPhong }} - Hệ Thống Quản Lý Nhà Trọ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/quan-ly-phong.css') }}">
    <script src="{{ asset('js/quan-ly-phong.js') }}" defer></script>
</head>
<body class="h-full font-sans antialiased text-zinc-900 bg-zinc-50">
    <div class="min-h-full">
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
                            class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-900 text-white">
                            Quản lý phòng
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

        <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6 flex items-center justify-between">
                <a href="{{ route('phong-tro.danh-sach') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-600 hover:text-zinc-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Quay lại danh sách phòng</span>
                </a>

                <div class="flex items-center gap-2">
                    <button type="button" data-hanh-dong="sua-phong"
                        data-ma-phong="{{ $phongTro->maPhong }}"
                        data-so-phong="{{ $phongTro->soPhong }}"
                        data-don-gia="{{ $phongTro->donGiaMacDinh }}"
                        data-ghi-chu="{{ $phongTro->ghiChu }}"
                        class="px-3 py-1.5 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors shadow-xs">
                        Sửa thông tin
                    </button>

                    @if ($phongTro->trangThaiPhong !== 'DANG_THUE')
                        <form action="{{ route('phong-tro.doi-trang-thai', $phongTro->maPhong) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="px-3 py-1.5 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors shadow-xs">
                                {{ $phongTro->trangThaiPhong === 'TRONG' ? 'Chuyển sang sửa chữa' : 'Chuyển sang sẵn sàng đón khách' }}
                            </button>
                        </form>

                        <button type="button" data-hanh-dong="xoa-phong"
                            data-ma-phong="{{ $phongTro->maPhong }}"
                            data-so-phong="{{ $phongTro->soPhong }}"
                            class="px-3 py-1.5 rounded-lg border border-red-200 bg-white text-red-600 text-xs font-medium hover:bg-red-50 transition-colors shadow-xs">
                            Ngưng hoạt động
                        </button>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs mb-6">
                <div class="flex items-start justify-between pb-4 border-b border-zinc-100 mb-4">
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl font-bold text-zinc-900">Phòng {{ $phongTro->soPhong }}</h2>
                            @if ($phongTro->trangThaiPhong === 'TRONG')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Trống
                                </span>
                            @elseif ($phongTro->trangThaiPhong === 'DANG_THUE')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-sky-50 text-sky-700 border border-sky-200">
                                    Đang thuê
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                    Đang sửa chữa
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-zinc-500 mt-1">Đơn giá niêm yết: <strong class="text-zinc-900 tabular-nums">{{ number_format($phongTro->donGiaMacDinh, 0, ',', '.') }} đ/tháng</strong></p>
                    </div>
                </div>

                <div class="text-xs text-zinc-700">
                    <span class="font-medium text-zinc-500 block mb-1">Ghi chú đặc điểm phòng:</span>
                    <p class="p-3 bg-zinc-50 rounded-lg border border-zinc-200 text-zinc-800">
                        {{ $phongTro->ghiChu ?: 'Chưa có ghi chú cho phòng này.' }}
                    </p>
                </div>
            </div>

            <!-- HỢP ĐỒNG HIỆN TẠI -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs mb-6">
                <h3 class="text-sm font-semibold text-zinc-900 mb-4 uppercase tracking-wider">Hợp đồng thuê hiện tại</h3>

                @if ($phongTro->hopDongHieuLuc && $phongTro->hopDongHieuLuc->khachThue)
                    @php
                        $hd = $phongTro->hopDongHieuLuc;
                        $khach = $hd->khachThue;
                    @endphp
                    <div class="rounded-lg border border-sky-200 bg-sky-50/40 p-4 text-xs space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <span class="text-zinc-500 block">Khách đại diện:</span>
                                <strong class="text-zinc-900 text-sm">{{ $khach->hoTen }}</strong>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Số điện thoại:</span>
                                <span class="text-zinc-800 font-medium">{{ $khach->soDienThoai }}</span>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Số CCCD:</span>
                                <span class="text-zinc-800">{{ $khach->soCCCD }}</span>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Địa chỉ thường trú:</span>
                                <span class="text-zinc-800">{{ $khach->diaChi ?: 'Chưa cập nhật' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-t border-sky-100">
                            <div>
                                <span class="text-zinc-500 block">Giá thuê chốt:</span>
                                <strong class="text-zinc-900 tabular-nums">{{ number_format($hd->giaThueChot, 0, ',', '.') }} đ/tháng</strong>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Tiền đặt cọc:</span>
                                <span class="text-zinc-800 tabular-nums">{{ number_format($hd->tienDatCoc, 0, ',', '.') }} đ</span>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Ngày bắt đầu thuê:</span>
                                <span class="text-zinc-800">{{ \Carbon\Carbon::parse($hd->ngayBatDau)->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        @if ($hd->nguoiOCung && $hd->nguoiOCung->count() > 0)
                            <div class="pt-4 border-t border-sky-100">
                                <span class="font-medium text-zinc-700 block mb-2 text-xs">Danh sách người ở cùng ({{ $hd->nguoiOCung->count() }} người):</span>
                                <div class="overflow-x-auto rounded-lg border border-sky-200 bg-white">
                                    <table class="min-w-full divide-y divide-sky-100 text-xs">
                                        <thead class="bg-sky-50 text-sky-800 font-medium">
                                            <tr>
                                                <th scope="col" class="px-3.5 py-2 text-left">Họ và tên</th>
                                                <th scope="col" class="px-3.5 py-2 text-left">Quan hệ</th>
                                                <th scope="col" class="px-3.5 py-2 text-right">Số điện thoại</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-sky-100">
                                            @foreach ($hd->nguoiOCung as $nguoi)
                                                <tr class="hover:bg-sky-50/50 transition-colors">
                                                    <td class="px-3.5 py-2 font-medium text-zinc-900">{{ $nguoi->hoTen }}</td>
                                                    <td class="px-3.5 py-2 text-zinc-600">{{ $nguoi->quanHe ?: 'Ở cùng' }}</td>
                                                    <td class="px-3.5 py-2 text-right tabular-nums text-zinc-700">{{ $nguoi->soDienThoai ?: '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-6 text-center text-xs text-zinc-500 bg-zinc-50 rounded-lg border border-zinc-200">
                        Hiện phòng này chưa có hợp đồng thuê nào đang hiệu lực.
                    </div>
                @endif
            </div>

            <!-- CHỈ SỐ ĐIỆN NƯỚC GẦN ĐÂY -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                <h3 class="text-sm font-semibold text-zinc-900 mb-4 uppercase tracking-wider">Lịch sử chỉ số điện nước gần nhất</h3>

                @if ($phongTro->chiSoDienNuoc && $phongTro->chiSoDienNuoc->count() > 0)
                    <div class="overflow-hidden rounded-lg border border-zinc-200">
                        <table class="min-w-full divide-y divide-zinc-200 text-xs">
                            <thead class="bg-zinc-50 text-zinc-500 text-left font-medium">
                                <tr>
                                    <th class="px-4 py-2.5">Tháng ghi nhận</th>
                                    <th class="px-4 py-2.5 text-right">Điện mới (kWh)</th>
                                    <th class="px-4 py-2.5 text-right">Tiêu thụ điện</th>
                                    <th class="px-4 py-2.5 text-right">Nước mới (m³)</th>
                                    <th class="px-4 py-2.5 text-right">Tiêu thụ nước</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 bg-white">
                                @foreach ($phongTro->chiSoDienNuoc as $cs)
                                    <tr>
                                        <td class="px-4 py-2.5 font-medium text-zinc-900">{{ \Carbon\Carbon::parse($cs->thangGhiNhan)->format('m/Y') }}</td>
                                        <td class="px-4 py-2.5 text-right text-zinc-700 tabular-nums">{{ $cs->chiSoDienMoi }}</td>
                                        <td class="px-4 py-2.5 text-right font-medium text-zinc-900 tabular-nums">{{ $cs->soDienTieuThu }} kWh</td>
                                        <td class="px-4 py-2.5 text-right text-zinc-700 tabular-nums">{{ $cs->chiSoNuocMoi }}</td>
                                        <td class="px-4 py-2.5 text-right font-medium text-zinc-900 tabular-nums">{{ $cs->soNuocTieuThu }} m³</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-xs text-zinc-500 bg-zinc-50 rounded-lg border border-zinc-200">
                        Chưa có dữ liệu ghi nhận chỉ số điện nước cho phòng này.
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- MODAL SỬA PHÒNG -->
    <div id="modalSuaPhong" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <h3 class="text-sm font-semibold text-zinc-900">Chỉnh sửa thông tin phòng</h3>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-base">&times;</button>
            </div>

            <form id="bieuMauSuaPhong" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4 text-xs">
                    <div>
                        <label for="soPhongSua" class="block font-medium text-zinc-700 mb-1">Số phòng <span class="text-red-500">*</span></label>
                        <input type="text" name="soPhong" id="soPhongSua" required
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>

                    <div>
                        <label for="donGiaSua" class="block font-medium text-zinc-700 mb-1">Đơn giá mặc định (đ) <span class="text-red-500">*</span></label>
                        <input type="number" name="donGiaMacDinh" id="donGiaSua" required min="0" step="50000"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                    </div>

                    <div>
                        <label for="ghiChuSua" class="block font-medium text-zinc-700 mb-1">Ghi chú đặc điểm phòng</label>
                        <textarea name="ghiChu" id="ghiChuSua" rows="3"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2 pt-4 border-t border-zinc-100">
                    <button type="button" data-dong-modal
                        class="px-3.5 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL XÁC NHẬN XÓA -->
    <div id="modalXacNhanXoa" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-sm w-full p-6 text-center">
            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-zinc-900 mb-1">Ngưng hoạt động phòng này?</h3>
            <p class="text-xs text-zinc-500 mb-4">
                Bạn có chắc chắn muốn ngưng hoạt động <strong id="nhanSoPhongXoa" class="text-zinc-900">phòng này</strong>? Phòng sẽ bị ẩn khỏi danh sách cho thuê nhưng dữ liệu lịch sử vẫn được bảo toàn.
            </p>

            <form id="bieuMauXoaPhong" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex items-center justify-center gap-2">
                    <button type="button" data-dong-modal
                        class="px-3.5 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white text-xs font-medium hover:bg-red-700 transition-colors shadow-xs">
                        Xác nhận ngừng
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

