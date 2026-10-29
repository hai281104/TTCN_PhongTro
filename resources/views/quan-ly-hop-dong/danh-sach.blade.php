<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Hợp Đồng Thuê - Hệ Thống Quản Lý Nhà Trọ</title>
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
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

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

            <!-- THANH TIÊU ĐỀ & NÚT LẬP HỢP ĐỒNG -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 mb-1">Quản Lý Hợp Đồng Thuê Phòng</h2>
                    <p class="text-xs text-zinc-500">Quản lý danh sách hợp đồng gắn khách thuê với phòng, tiền đặt cọc và nhân khẩu ở cùng.</p>
                </div>
                <button type="button" data-hanh-dong="mo-modal-tao-hop-dong"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs self-start sm:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Lập hợp đồng mới</span>
                </button>
            </div>

            <!-- THẺ THỐNG KÊ (KPIs) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                    <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Tổng số hợp đồng</div>
                    <div class="text-2xl font-bold text-zinc-900 mt-2 tabular-nums">{{ $tongHopDong }}</div>
                    <div class="text-xs text-zinc-500 mt-1">Toàn bộ lịch sử</div>
                </div>

                <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                    <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Đang hiệu lực</div>
                    <div class="text-2xl font-bold text-sky-700 mt-2 tabular-nums">{{ $soHopDongHieuLuc }}</div>
                    <div class="text-xs text-zinc-500 mt-1">Phòng đang có khách thuê</div>
                </div>

                <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                    <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Đã kết thúc</div>
                    <div class="text-2xl font-bold text-zinc-600 mt-2 tabular-nums">{{ $soHopDongDaKetThuc }}</div>
                    <div class="text-xs text-zinc-500 mt-1">Đã trả phòng / thanh lý</div>
                </div>

                <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                    <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Tiền cọc đang giữ</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-2 tabular-nums">
                        {{ number_format($tongTienCocDangGiu, 0, ',', '.') }} đ
                    </div>
                    <div class="text-xs text-zinc-500 mt-1">Từ các hợp đồng hiệu lực</div>
                </div>
            </div>

            <!-- BỘ LỌC VÀ TÌM KIẾM -->
            <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                <form action="{{ route('hop-dong.danh-sach') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
                    <div class="sm:col-span-6">
                        <label for="tuKhoa" class="block font-medium text-zinc-700 mb-1">Tìm kiếm</label>
                        <input type="text" name="tuKhoa" id="tuKhoa" value="{{ request('tuKhoa') }}"
                            placeholder="Số phòng, họ tên khách, CCCD, số điện thoại..."
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>

                    <div class="sm:col-span-4">
                        <label for="trangThaiHD" class="block font-medium text-zinc-700 mb-1">Trạng thái hợp đồng</label>
                        <select name="trangThaiHD" id="trangThaiHD"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="HIEU_LUC" {{ request('trangThaiHD') === 'HIEU_LUC' ? 'selected' : '' }}>Đang hiệu lực</option>
                            <option value="DA_KET_THUC" {{ request('trangThaiHD') === 'DA_KET_THUC' ? 'selected' : '' }}>Đã kết thúc</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 flex items-end gap-2">
                        <button type="submit"
                            class="w-full px-3.5 py-2 rounded-lg bg-zinc-900 text-white font-medium hover:bg-zinc-800 transition-colors">
                            Lọc
                        </button>
                        @if (request()->hasAny(['tuKhoa', 'trangThaiHD']))
                            <a href="{{ route('hop-dong.danh-sach') }}"
                                class="px-3 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 transition-colors">
                                Xóa
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- BẢNG DANH SÁCH HỢP ĐỒNG -->
            <div class="bg-white border border-zinc-200 rounded-xl shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200 text-xs">
                        <thead class="bg-zinc-50 text-zinc-600 font-medium text-left">
                            <tr>
                                <th scope="col" class="px-4 py-3">Mã HĐ</th>
                                <th scope="col" class="px-4 py-3">Phòng</th>
                                <th scope="col" class="px-4 py-3">Khách thuê đại diện</th>
                                <th scope="col" class="px-4 py-3 text-right">Giá thuê chốt</th>
                                <th scope="col" class="px-4 py-3 text-right">Tiền đặt cọc</th>
                                <th scope="col" class="px-4 py-3">Thời gian thuê</th>
                                <th scope="col" class="px-4 py-3 text-center">Trạng thái</th>
                                <th scope="col" class="px-4 py-3 text-center">Nhân khẩu</th>
                                <th scope="col" class="px-4 py-3 text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 bg-white">
                            @forelse ($danhSachHopDong as $hd)
                                <tr class="hover:bg-zinc-50/70 transition-colors">
                                    <td class="px-4 py-3.5 font-bold text-zinc-900 whitespace-nowrap">
                                        #HD-{{ str_pad($hd->maHopDong, 4, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <a href="{{ route('phong-tro.chi-tiet', $hd->maPhong) }}" class="font-bold text-zinc-900 hover:underline">
                                            Phòng {{ optional($hd->phongTro)->soPhong ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if ($hd->khachThue)
                                            <a href="{{ route('khach-thue.chi-tiet', $hd->maKhach) }}" class="font-medium text-zinc-900 hover:underline block">
                                                {{ $hd->khachThue->hoTen }}
                                            </a>
                                            <span class="text-zinc-500 text-[11px] block">{{ $hd->khachThue->soDienThoai }}</span>
                                        @else
                                            <span class="text-zinc-400 italic">Không tìm thấy</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-right font-medium text-zinc-900 tabular-nums whitespace-nowrap">
                                        {{ number_format($hd->giaThueChot, 0, ',', '.') }} đ
                                    </td>
                                    <td class="px-4 py-3.5 text-right tabular-nums text-emerald-700 font-medium whitespace-nowrap">
                                        {{ number_format($hd->tienDatCoc, 0, ',', '.') }} đ
                                    </td>
                                    <td class="px-4 py-3.5 text-zinc-600 whitespace-nowrap">
                                        <div>{{ \Carbon\Carbon::parse($hd->ngayBatDau)->format('d/m/Y') }}</div>
                                        <div class="text-[11px] text-zinc-400">
                                            {{ $hd->ngayKetThuc ? '&rarr; ' . \Carbon\Carbon::parse($hd->ngayKetThuc)->format('d/m/Y') : '(Vô thời hạn)' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
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
                                    <td class="px-4 py-3.5 text-center whitespace-nowrap text-zinc-600">
                                        {{ $hd->nguoiOCung ? $hd->nguoiOCung->count() + 1 : 1 }} người
                                    </td>
                                    <td class="px-4 py-3.5 text-right whitespace-nowrap space-x-1">
                                        <a href="{{ route('hop-dong.chi-tiet', $hd->maHopDong) }}"
                                            class="inline-block px-2.5 py-1 rounded border border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-100 transition-colors">
                                            Chi tiết
                                        </a>

                                        @if ($hd->trangThaiHD === 'HIEU_LUC')
                                            <button type="button"
                                                data-hanh-dong="mo-modal-ket-thuc"
                                                data-ma-hop-dong="{{ $hd->maHopDong }}"
                                                data-so-phong="{{ optional($hd->phongTro)->soPhong }}"
                                                data-tien-coc="{{ $hd->tienDatCoc }}"
                                                class="px-2.5 py-1 rounded border border-red-200 bg-white text-red-600 hover:bg-red-50 transition-colors">
                                                Thanh lý
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-zinc-500">
                                        Không tìm thấy hợp đồng nào phù hợp với điều kiện tìm kiếm.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($danhSachHopDong->hasPages())
                    <div class="px-4 py-3 border-t border-zinc-200 bg-zinc-50">
                        {{ $danhSachHopDong->links() }}
                    </div>
                @endif
            </div>

        </main>
    </div>

    <!-- MODAL LẬP HỢP ĐỒNG MỚI -->
    <div id="modalTaoHopDong" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-zinc-900">Lập Hợp Đồng Thuê Phòng Mới</h3>
                    <p class="text-xs text-zinc-500">Phòng sẽ tự động chuyển sang trạng thái "Đang thuê" sau khi tạo hợp đồng.</p>
                </div>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-lg">&times;</button>
            </div>

            <form action="{{ route('hop-dong.tao-moi') }}" method="POST">
                @csrf
                <div class="space-y-4 text-xs">
                    <!-- Chọn phòng và khách thuê -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="chonMaPhong" class="block font-medium text-zinc-700 mb-1">
                                Chọn phòng trống <span class="text-red-500">*</span>
                            </label>
                            <select name="maPhong" id="chonMaPhong" required
                                class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                                <option value="">-- Chọn phòng trọ còn trống --</option>
                                @foreach ($danhSachPhongTrong as $p)
                                    <option value="{{ $p->maPhong }}" data-don-gia="{{ $p->donGiaMacDinh }}">
                                        Phòng {{ $p->soPhong }} ({{ number_format($p->donGiaMacDinh, 0, ',', '.') }} đ/tháng)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="chonMaKhach" class="block font-medium text-zinc-700 mb-1">
                                Khách đại diện ký hợp đồng <span class="text-red-500">*</span>
                            </label>
                            <select name="maKhach" id="chonMaKhach" required
                                class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                                <option value="">-- Chọn khách thuê đại diện --</option>
                                @foreach ($danhSachKhachThue as $k)
                                    <option value="{{ $k->maKhach }}">
                                        {{ $k->hoTen }} ({{ $k->soDienThoai }} - CCCD: {{ $k->soCCCD }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Thời gian thuê -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="ngayBatDau" class="block font-medium text-zinc-700 mb-1">
                                Ngày bắt đầu tính tiền <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="ngayBatDau" id="ngayBatDau" required value="{{ date('Y-m-d') }}"
                                class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                        </div>

                        <div>
                            <label for="ngayKetThuc" class="block font-medium text-zinc-700 mb-1">
                                Ngày hết hạn hợp đồng (tùy chọn)
                            </label>
                            <input type="date" name="ngayKetThuc" id="ngayKetThuc"
                                class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                        </div>
                    </div>

                    <!-- Giá thuê và tiền cọc -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="inputGiaThueChot" class="block font-medium text-zinc-700 mb-1">
                                Giá thuê chốt (đ/tháng) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="giaThueChot" id="inputGiaThueChot" required min="0" step="50000"
                                class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                        </div>

                        <div>
                            <label for="inputTienDatCoc" class="block font-medium text-zinc-700 mb-1">
                                Tiền đặt cọc giữ chỗ (đ) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="tienDatCoc" id="inputTienDatCoc" required min="0" step="50000"
                                class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                        </div>
                    </div>

                    <!-- Danh sách người ở cùng động -->
                    <div class="pt-3 border-t border-zinc-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-zinc-800">Người ở cùng trong phòng (tùy chọn)</span>
                            <button type="button" data-hanh-dong="them-dong-nguoi-o"
                                class="inline-flex items-center gap-1 text-[11px] font-medium text-sky-700 hover:text-sky-900">
                                <span>+ Thêm người ở cùng</span>
                            </button>
                        </div>
                        <div id="danhSachDongNguoiO" class="space-y-2"></div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2 pt-4 border-t border-zinc-100">
                    <button type="button" data-dong-modal
                        class="px-3.5 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                        Xác nhận lập hợp đồng
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
                Bạn đang thực hiện kết thúc hợp đồng thuê <strong id="spanSoPhongKetThuc" class="text-zinc-900">phòng</strong>. Phòng sẽ tự động chuyển về trạng thái <strong>"Trống"</strong> để đón khách mới.
            </p>

            <form id="bieuMauKetThucHopDong" action="" method="POST">
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
                        <input type="number" name="tienHoanCoc" id="inputTienHoanCoc" min="0" step="50000"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
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
