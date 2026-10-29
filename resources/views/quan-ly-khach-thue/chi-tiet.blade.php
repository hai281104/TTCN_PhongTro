<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ Sơ Khách Thuê: {{ $khachThue->hoTen }} - Hệ Thống Quản Lý Nhà Trọ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/quan-ly-khach.css') }}">
    <script src="{{ asset('js/quan-ly-khach.js') }}" defer></script>
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
                            class="px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
                            Quản lý phòng
                        </a>
                        <a href="{{ route('khach-thue.danh-sach') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-900 text-white">
                            Khách thuê
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
                <a href="{{ route('khach-thue.danh-sach') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-zinc-600 hover:text-zinc-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Quay lại danh sách khách thuê</span>
                </a>

                <div class="flex items-center gap-2">
                    <button type="button" data-hanh-dong="sua-khach"
                        data-ma-khach="{{ $khachThue->maKhach }}"
                        data-ho-ten="{{ $khachThue->hoTen }}"
                        data-so-cccd="{{ $khachThue->soCCCD }}"
                        data-so-dien-thoai="{{ $khachThue->soDienThoai }}"
                        data-dia-chi="{{ $khachThue->diaChi }}"
                        class="px-3 py-1.5 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors shadow-xs">
                        Sửa thông tin
                    </button>

                    @if (!$khachThue->maNguoiDung)
                        <button type="button" data-hanh-dong="cap-tai-khoan"
                            data-ma-khach="{{ $khachThue->maKhach }}"
                            data-ho-ten="{{ $khachThue->hoTen }}"
                            class="px-3 py-1.5 rounded-lg border border-emerald-200 bg-white text-emerald-700 text-xs font-medium hover:bg-emerald-50 transition-colors shadow-xs">
                            Cấp tài khoản
                        </button>
                    @endif

                    @if (!$khachThue->hopDongHieuLuc)
                        <button type="button" data-hanh-dong="xoa-khach"
                            data-ma-khach="{{ $khachThue->maKhach }}"
                            data-ho-ten="{{ $khachThue->hoTen }}"
                            class="px-3 py-1.5 rounded-lg border border-red-200 bg-white text-red-600 text-xs font-medium hover:bg-red-50 transition-colors shadow-xs">
                            Ngưng hoạt động
                        </button>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs mb-6">
                <div class="flex items-start justify-between pb-4 border-b border-zinc-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-zinc-900 text-white flex items-center justify-center font-bold text-base shadow-xs">
                            {{ mb_substr($khachThue->hoTen, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-xl font-bold text-zinc-900">{{ $khachThue->hoTen }}</h2>
                                @if ($khachThue->hopDongHieuLuc && $khachThue->hopDongHieuLuc->phongTro)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-sky-50 text-sky-700 border border-sky-200">
                                        Đang thuê {{ $khachThue->hopDongHieuLuc->phongTro->soPhong }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-zinc-100 text-zinc-600 border border-zinc-200">
                                        Chưa thuê phòng
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-zinc-500 mt-0.5">SĐT: {{ $khachThue->soDienThoai }} | CCCD: {{ $khachThue->soCCCD }}</div>
                        </div>
                    </div>

                    <div>
                        @if ($khachThue->nguoiDung)
                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Tài khoản: {{ $khachThue->nguoiDung->email }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                Chưa có tài khoản
                            </span>
                        @endif
                    </div>
                </div>

                <div class="text-xs text-zinc-700">
                    <span class="font-medium text-zinc-500 block mb-1">Địa chỉ thường trú / Quê quán:</span>
                    <p class="p-3 bg-zinc-50 rounded-lg border border-zinc-200 text-zinc-800">
                        {{ $khachThue->diaChi ?: 'Chưa cập nhật thông tin địa chỉ thường trú.' }}
                    </p>
                </div>
            </div>

            <!-- HỢP ĐỒNG ĐANG HIỆU LỰC -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs mb-6">
                <h3 class="text-sm font-semibold text-zinc-900 mb-4 uppercase tracking-wider">Hợp đồng thuê đang hiệu lực</h3>

                @if ($khachThue->hopDongHieuLuc)
                    @php
                        $hd = $khachThue->hopDongHieuLuc;
                        $phong = $hd->phongTro;
                    @endphp
                    <div class="rounded-lg border border-sky-200 bg-sky-50/40 p-4 text-xs space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <span class="text-zinc-500 block">Phòng thuê:</span>
                                <strong class="text-zinc-900 text-sm">{{ $phong ? $phong->soPhong : 'Chưa xác định' }}</strong>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Giá thuê chốt:</span>
                                <strong class="text-zinc-900 tabular-nums">{{ number_format($hd->giaThueChot, 0, ',', '.') }} đ/tháng</strong>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Tiền đặt cọc:</span>
                                <span class="text-zinc-800 tabular-nums">{{ number_format($hd->tienDatCoc, 0, ',', '.') }} đ</span>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Ngày bắt đầu:</span>
                                <span class="text-zinc-800">{{ \Carbon\Carbon::parse($hd->ngayBatDau)->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        @if ($hd->nguoiOCung && $hd->nguoiOCung->count() > 0)
                            <div class="pt-3 border-t border-sky-100">
                                <span class="font-medium text-zinc-700 block mb-2">Người ở cùng phòng ({{ $hd->nguoiOCung->count() }} người):</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach ($hd->nguoiOCung as $nguoi)
                                        <div class="p-2.5 rounded bg-white border border-sky-200 flex items-center justify-between">
                                            <div>
                                                <span class="font-medium text-zinc-900">{{ $nguoi->hoTen }}</span>
                                                <span class="text-[11px] text-zinc-500 block">Quan hệ: {{ $nguoi->quanHe ?: 'Ở cùng' }}</span>
                                            </div>
                                            <span class="text-[11px] text-zinc-600">{{ $nguoi->soDienThoai ?: 'Không có SĐT' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-6 text-center text-xs text-zinc-500 bg-zinc-50 rounded-lg border border-zinc-200">
                        Khách hiện không có hợp đồng thuê phòng nào đang hiệu lực.
                    </div>
                @endif
            </div>

            <!-- LỊCH SỬ THUÊ PHÒNG -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                <h3 class="text-sm font-semibold text-zinc-900 mb-4 uppercase tracking-wider">Lịch sử các hợp đồng thuê</h3>

                @if ($khachThue->hopDong && $khachThue->hopDong->count() > 0)
                    <div class="overflow-hidden rounded-lg border border-zinc-200">
                        <table class="min-w-full divide-y divide-zinc-200 text-xs">
                            <thead class="bg-zinc-50 text-zinc-500 text-left font-medium">
                                <tr>
                                    <th class="px-4 py-2.5">Phòng</th>
                                    <th class="px-4 py-2.5">Ngày bắt đầu</th>
                                    <th class="px-4 py-2.5">Ngày kết thúc</th>
                                    <th class="px-4 py-2.5 text-right">Giá chốt</th>
                                    <th class="px-4 py-2.5 text-right">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 bg-white">
                                @foreach ($khachThue->hopDong as $hd)
                                    <tr>
                                        <td class="px-4 py-2.5 font-medium text-zinc-900">{{ $hd->phongTro ? $hd->phongTro->soPhong : 'P.?' }}</td>
                                        <td class="px-4 py-2.5 text-zinc-700">{{ \Carbon\Carbon::parse($hd->ngayBatDau)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2.5 text-zinc-700">{{ $hd->ngayKetThuc ? \Carbon\Carbon::parse($hd->ngayKetThuc)->format('d/m/Y') : 'Đang thuê' }}</td>
                                        <td class="px-4 py-2.5 text-right font-medium text-zinc-900 tabular-nums">{{ number_format($hd->giaThueChot, 0, ',', '.') }} đ</td>
                                        <td class="px-4 py-2.5 text-right">
                                            @if ($hd->trangThaiHD === 'HIEU_LUC')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Hiệu lực
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-zinc-100 text-zinc-600 border border-zinc-200">
                                                    Đã kết thúc
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
                        Chưa có lịch sử hợp đồng thuê phòng nào.
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- MODAL SỬA KHÁCH THUÊ -->
    <div id="modalSuaKhach" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <h3 class="text-sm font-semibold text-zinc-900">Chỉnh sửa hồ sơ khách thuê</h3>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-base">&times;</button>
            </div>

            <form id="bieuMauSuaKhach" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4 text-xs">
                    <div>
                        <label for="hoTenSua" class="block font-medium text-zinc-700 mb-1">Họ và tên <span class="text-red-500">*</span></label>
                        <input type="text" name="hoTen" id="hoTenSua" required
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="soCCCDSua" class="block font-medium text-zinc-700 mb-1">Số CCCD (12 số) <span class="text-red-500">*</span></label>
                            <input type="text" name="soCCCD" id="soCCCDSua" required maxlength="12"
                                class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                        </div>

                        <div>
                            <label for="soDienThoaiSua" class="block font-medium text-zinc-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                            <input type="text" name="soDienThoai" id="soDienThoaiSua" required
                                class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                        </div>
                    </div>

                    <div>
                        <label for="diaChiSua" class="block font-medium text-zinc-700 mb-1">Địa chỉ thường trú / Quê quán</label>
                        <input type="text" name="diaChi" id="diaChiSua"
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
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CẤP TÀI KHOẢN -->
    <div id="modalCapTaiKhoan" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-sm w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <h3 class="text-sm font-semibold text-zinc-900">Cấp tài khoản đăng nhập</h3>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-base">&times;</button>
            </div>

            <p class="text-xs text-zinc-500 mb-4">
                Cấp tài khoản cổng khách thuê cho <strong id="nhanTenKhachCapTK" class="text-zinc-900">khách này</strong>.
            </p>

            <form id="bieuMauCapTaiKhoan" action="" method="POST">
                @csrf
                <div class="space-y-4 text-xs">
                    <div>
                        <label for="emailCapMoi" class="block font-medium text-zinc-700 mb-1">Email đăng nhập <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="emailCapMoi" required placeholder="khach@example.com"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>

                    <div>
                        <label for="matKhauCapMoi" class="block font-medium text-zinc-700 mb-1">Mật khẩu khởi tạo <span class="text-red-500">*</span></label>
                        <input type="password" name="matKhau" id="matKhauCapMoi" required minlength="6" placeholder="Tối thiểu 6 ký tự"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2 pt-4 border-t border-zinc-100">
                    <button type="button" data-dong-modal
                        class="px-3.5 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition-colors shadow-xs">
                        Cấp tài khoản
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL XÁC NHẬN XÓA -->
    <div id="modalXacNhanXoaKhach" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-sm w-full p-6 text-center">
            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-zinc-900 mb-1">Ngưng hoạt động hồ sơ khách này?</h3>
            <p class="text-xs text-zinc-500 mb-4">
                Bạn có chắc muốn ngưng hoạt động hồ sơ của <strong id="nhanTenKhachXoa" class="text-zinc-900">khách này</strong>? Hồ sơ sẽ bị ẩn khỏi danh sách nhưng lịch sử thuê phòng và hóa đơn vẫn được bảo toàn.
            </p>

            <form id="bieuMauXoaKhach" action="" method="POST">
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

