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
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
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
                        <a href="{{ route('khach-o-cung.danh-sach') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
                            Khách ở cùng
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
        <main class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

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
                    <p class="text-xs text-zinc-500">Ký hợp đồng trực tiếp với 1 khách thuê đại diện, xác định số người ở và bảo toàn cọc phòng.</p>
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
                        <thead class="bg-zinc-50 text-zinc-600 font-medium text-left border-b border-zinc-200">
                            <tr>
                                <th scope="col" class="px-4 py-3">Mã HĐ</th>
                                <th scope="col" class="px-4 py-3">Phòng & Thể loại</th>
                                <th scope="col" class="px-4 py-3">Khách thuê đại diện</th>
                                <th scope="col" class="px-4 py-3 text-center">Số người ở</th>
                                <th scope="col" class="px-4 py-3 text-right">Giá thuê chốt</th>
                                <th scope="col" class="px-4 py-3 text-right">Tiền đặt cọc</th>
                                <th scope="col" class="px-4 py-3">Thời gian thuê</th>
                                <th scope="col" class="px-4 py-3 text-center">Trạng thái</th>
                                <th scope="col" class="px-4 py-3 text-right sticky right-0 bg-zinc-50 z-10 shadow-[-6px_0_10px_-4px_rgba(0,0,0,0.06)]">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 bg-white">
                            @forelse ($danhSachHopDong as $hd)
                                <tr class="hover:bg-zinc-50/70 transition-colors group">
                                    <td class="px-4 py-3.5 font-bold text-zinc-900 whitespace-nowrap">
                                        #HD-{{ str_pad($hd->maHopDong, 4, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <a href="{{ route('phong-tro.chi-tiet', $hd->maPhong) }}" class="font-bold text-zinc-900 hover:underline">
                                            Phòng {{ optional($hd->phongTro)->soPhong ?? 'N/A' }}
                                        </a>
                                        <div class="text-[11px] text-zinc-500">
                                            {{ optional(optional($hd->phongTro)->loaiPhong)->tenLoaiPhong ?? 'Chưa phân loại' }}
                                        </div>
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
                                    <td class="px-4 py-3.5 text-center whitespace-nowrap text-zinc-700 font-medium">
                                        <span class="font-bold text-sky-700">{{ $hd->soLuongNguoi ?? 1 }}</span> người
                                        @if ($hd->phongTro)
                                            <span class="text-zinc-400 text-[11px] font-normal block">(Tối đa {{ $hd->phongTro->soNguoiToiDa }} người)</span>
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
                                    <td class="px-4 py-3.5 text-right whitespace-nowrap space-x-1 sticky right-0 bg-white group-hover:bg-zinc-50 z-10 shadow-[-6px_0_10px_-4px_rgba(0,0,0,0.06)]">
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

    <!-- MODAL LẬP HỢP ĐỒNG MỚI (1 người đại diện ký, xác định số người ở) -->
    <div id="modalTaoHopDong" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-zinc-900">Lập Hợp Đồng Thuê Phòng Mới</h3>
                    <p class="text-xs text-zinc-500">1 khách thuê làm đại diện ký kết. Phòng tự động chuyển sang "Đang thuê".</p>
                </div>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-lg">&times;</button>
            </div>

            <form action="{{ route('hop-dong.tao-moi') }}" method="POST">
                @csrf
                <div class="space-y-4 text-xs">
                    <!-- Chọn phòng và khách thuê (Có tìm kiếm nhanh theo tên / số phòng) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- CHỌN PHÒNG TRỐNG (CÓ TÌM KIẾM) -->
                        <div class="relative hop-chon-tim-kiem" id="hopChonPhong">
                            <label class="block font-medium text-zinc-700 mb-1">
                                Chọn phòng trống <span class="text-red-500">*</span>
                            </label>
                            <input type="hidden" name="maPhong" id="chonMaPhong" required value="{{ old('maPhong') }}">

                            <button type="button" class="nut-kich-hoat-chon w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-left text-zinc-900 flex items-center justify-between focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-colors">
                                <span class="nhan-hien-thi text-zinc-500">-- Chọn phòng trọ còn trống --</span>
                                <svg class="w-4 h-4 text-zinc-400 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div class="bang-lua-chon hidden absolute z-30 mt-1 w-full bg-white border border-zinc-200 rounded-lg shadow-lg max-h-60 overflow-hidden flex flex-col">
                                <div class="p-2 border-b border-zinc-100 bg-zinc-50">
                                    <div class="relative">
                                        <input type="text" class="o-nhap-tim-kiem w-full px-2.5 py-1.5 pl-7 bg-white border border-zinc-300 rounded text-xs text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900"
                                            placeholder="Gõ số phòng để lọc...">
                                        <svg class="w-3.5 h-3.5 text-zinc-400 absolute left-2 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="danh-sach-muc overflow-y-auto max-h-48 divide-y divide-zinc-50 text-xs">
                                    @foreach ($danhSachPhongTrong as $p)
                                        <div class="muc-lua-chon px-3 py-2 cursor-pointer hover:bg-zinc-100 transition-colors flex items-center justify-between"
                                            data-gia-tri="{{ $p->maPhong }}"
                                            data-nhan="Phòng {{ $p->soPhong }} ({{ $p->loaiPhong->tenLoaiPhong ?? 'Phòng' }} - {{ number_format($p->donGiaMacDinh, 0, ',', '.') }} đ)"
                                            data-don-gia="{{ $p->donGiaMacDinh }}"
                                            data-tien-coc-mac-dinh="{{ ($p->tienCocMacDinh ?? 0) > 0 ? $p->tienCocMacDinh : $p->donGiaMacDinh }}"
                                            data-so-nguoi-toi-da="{{ $p->soNguoiToiDa }}"
                                            data-tim-kiem="Phòng {{ $p->soPhong }} {{ $p->soPhong }} {{ optional($p->loaiPhong)->tenLoaiPhong }} {{ $p->donGiaMacDinh }}">
                                            <div>
                                                <span class="font-bold text-zinc-900">Phòng {{ $p->soPhong }}</span>
                                                <span class="text-zinc-500 text-[11px] block">{{ optional($p->loaiPhong)->tenLoaiPhong ?? 'Tiêu chuẩn' }} (Tối đa {{ $p->soNguoiToiDa }} người)</span>
                                            </div>
                                            <span class="text-zinc-700 font-medium tabular-nums">{{ number_format($p->donGiaMacDinh, 0, ',', '.') }} đ</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="thong-bao-khong-tim-thay hidden p-3 text-center text-xs text-zinc-400 italic">
                                    Không tìm thấy phòng phù hợp
                                </div>
                            </div>
                        </div>

                        <!-- CHỌN KHÁCH ĐẠI DIỆN KÝ HỢP ĐỒNG (1 NGƯỜI ĐẠI DIỆN DUY NHẤT) -->
                        <div class="relative hop-chon-tim-kiem" id="hopChonKhach">
                            <label class="block font-medium text-zinc-700 mb-1">
                                Người đại diện ký hợp đồng <span class="text-red-500">*</span>
                            </label>
                            <input type="hidden" name="maKhach" id="chonMaKhach" required value="{{ old('maKhach') }}">

                            <button type="button" class="nut-kich-hoat-chon w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-left text-zinc-900 flex items-center justify-between focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-colors">
                                <span class="nhan-hien-thi text-zinc-500">-- Chọn khách thuê đại diện --</span>
                                <svg class="w-4 h-4 text-zinc-400 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div class="bang-lua-chon hidden absolute z-30 mt-1 w-full bg-white border border-zinc-200 rounded-lg shadow-lg max-h-60 overflow-hidden flex flex-col">
                                <div class="p-2 border-b border-zinc-100 bg-zinc-50">
                                    <div class="relative">
                                        <input type="text" class="o-nhap-tim-kiem w-full px-2.5 py-1.5 pl-7 bg-white border border-zinc-300 rounded text-xs text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900"
                                            placeholder="Gõ tên khách, SĐT hoặc CCCD để lọc...">
                                        <svg class="w-3.5 h-3.5 text-zinc-400 absolute left-2 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="danh-sach-muc overflow-y-auto max-h-48 divide-y divide-zinc-50 text-xs">
                                    @foreach ($danhSachKhachThue as $k)
                                        <div class="muc-lua-chon px-3 py-2 cursor-pointer hover:bg-zinc-100 transition-colors"
                                            data-gia-tri="{{ $k->maKhach }}"
                                            data-nhan="{{ $k->hoTen }} ({{ $k->soDienThoai }} - CCCD: {{ $k->soCCCD }})"
                                            data-tim-kiem="{{ $k->hoTen }} {{ $k->soDienThoai }} {{ $k->soCCCD }}">
                                            <div class="font-semibold text-zinc-900">{{ $k->hoTen }}</div>
                                            <div class="text-[11px] text-zinc-500 flex items-center gap-2">
                                                <span>SĐT: <strong class="text-zinc-700">{{ $k->soDienThoai }}</strong></span>
                                                <span>&bull;</span>
                                                <span>CCCD: {{ $k->soCCCD }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="thong-bao-khong-tim-thay hidden p-3 text-center text-xs text-zinc-400 italic">
                                    Không tìm thấy khách thuê phù hợp
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quy tắc số lượng người ở theo từng bước nghiệp vụ -->
                    <div class="p-3 bg-zinc-50 rounded-lg border border-zinc-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                            <div>
                                <div class="font-medium text-zinc-800">Số người khởi tạo hợp đồng: <strong>01 người (Người đại diện)</strong></div>
                                <span class="text-[11px] text-zinc-500">Nếu phòng có thêm bạn ở cùng, sau khi ký hợp đồng hãy sang mục <strong>Khách ở cùng</strong> để thêm từng người.</span>
                            </div>
                            <span id="nhanSucChuaPhong" class="text-xs text-zinc-600 font-medium shrink-0"></span>
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
                            <input type="text" inputmode="numeric" name="giaThueChot" id="inputGiaThueChot" required placeholder="Ví dụ: 2.500.000"
                                value="{{ old('giaThueChot') ? number_format(str_replace(['.', ','], '', old('giaThueChot')), 0, ',', '.') : '' }}"
                                class="dinh-dang-tien w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                        </div>

                        <div>
                            <label for="inputTienDatCoc" class="block font-medium text-zinc-700 mb-1">
                                Tiền đặt cọc giữ chỗ (đ) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" inputmode="numeric" name="tienDatCoc" id="inputTienDatCoc" required placeholder="Ví dụ: 2.500.000"
                                value="{{ old('tienDatCoc') ? number_format(str_replace(['.', ','], '', old('tienDatCoc')), 0, ',', '.') : '' }}"
                                class="dinh-dang-tien w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                        </div>
                    </div>

                    <div>
                        <label for="ghiChu" class="block font-medium text-zinc-700 mb-1">Ghi chú điều khoản hợp đồng</label>
                        <textarea name="ghiChu" id="ghiChu" rows="2" placeholder="Ghi chú thêm về điều khoản hợp đồng..."
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

            <form id="bieuMauKetThucHopDong" action="" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-4 text-xs">
                    <p class="text-zinc-600 leading-relaxed">
                        Bạn đang chuẩn bị thanh lý hợp đồng cho <strong id="nhanSoPhongKetThuc" class="text-zinc-900">phòng</strong>.
                        Sau khi xác nhận, hợp đồng sẽ chuyển thành <span class="font-medium text-zinc-800">Đã kết thúc</span> và trạng thái phòng sẽ tự động hoàn về <span class="font-medium text-emerald-700">Trống</span>.
                    </p>

                    <div>
                        <label for="ngayKetThucThanhLy" class="block font-medium text-zinc-700 mb-1">
                            Ngày kết thúc thực tế <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="ngayKetThuc" id="ngayKetThucThanhLy" required value="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>

                    <div>
                        <label for="tienHoanCoc" class="block font-medium text-zinc-700 mb-1">
                            Số tiền cọc hoàn lại cho khách (đ) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" inputmode="numeric" name="tienHoanCoc" id="tienHoanCoc" required
                            class="dinh-dang-tien w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                        <span class="text-[11px] text-zinc-400 mt-1 block">
                            Số tiền cọc ban đầu: <span id="nhanTienCocGoc" class="font-medium text-zinc-700 tabular-nums">0 đ</span>
                        </span>
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
