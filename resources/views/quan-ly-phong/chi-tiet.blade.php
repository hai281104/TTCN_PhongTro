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
                        <a href="{{ route('khach-thue.danh-sach') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
                            Khách thuê
                        </a>
                        <a href="{{ route('hop-dong.danh-sach') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
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
                        data-ma-loai-phong="{{ $phongTro->maLoaiPhong }}"
                        data-tang="{{ $phongTro->tang }}"
                        data-dien-tich="{{ $phongTro->dienTich }}"
                        data-tien-coc="{{ $phongTro->tienCocMacDinh }}"
                        data-tien-nghi="{{ json_encode($phongTro->tienNghi ?: []) }}"
                        data-so-nguoi-toi-da="{{ $phongTro->soNguoiToiDa }}"
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

            <!-- THÔNG TIN TỔNG QUAN PHÒNG -->
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
                        <div class="flex items-center gap-3 text-xs text-zinc-500 mt-1">
                            <span>Vị trí: <strong class="text-zinc-800">{{ $phongTro->tang ?: 'Tầng 1' }}</strong></span>
                            <span>&bull;</span>
                            <span>Diện tích: <strong class="text-zinc-800">{{ $phongTro->dienTich ? $phongTro->dienTich . ' m²' : ($phongTro->loaiPhong->dienTich ? $phongTro->loaiPhong->dienTich . ' m²' : '—') }}</strong></span>
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-lg font-bold text-zinc-900 tabular-nums">{{ number_format($phongTro->donGiaMacDinh, 0, ',', '.') }} đ<span class="text-xs font-normal text-zinc-500">/tháng</span></div>
                        <div class="text-[11px] text-zinc-500 mt-0.5">Tiền cọc gợi ý: <strong class="text-zinc-800 tabular-nums">{{ number_format($phongTro->tienCocMacDinh ?: $phongTro->donGiaMacDinh, 0, ',', '.') }} đ</strong></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs mb-4">
                    <div class="p-3 bg-zinc-50 rounded-lg border border-zinc-200">
                        <span class="font-medium text-zinc-500 block mb-1">Thể loại phòng & Quy mô:</span>
                        <div class="text-zinc-900 font-semibold">{{ $phongTro->loaiPhong->tenLoaiPhong ?? 'Chưa phân loại' }}</div>
                        <div class="text-[11px] text-zinc-500 mt-0.5">Sức chứa tối đa: {{ $phongTro->soNguoiToiDa }} người</div>
                    </div>

                    <div class="p-3 bg-zinc-50 rounded-lg border border-zinc-200">
                        <span class="font-medium text-zinc-500 block mb-1">Ghi chú đặc điểm phòng:</span>
                        <p class="text-zinc-800">{{ $phongTro->ghiChu ?: 'Chưa có ghi chú đặc biệt cho phòng này.' }}</p>
                    </div>
                </div>

                <!-- TIỆN NGHI NỘI THẤT -->
                <div>
                    <span class="font-semibold text-zinc-800 text-xs block mb-2">Tiện nghi & Trang thiết bị phòng:</span>
                    @php
                        $tienNghiArr = is_array($phongTro->tienNghi) ? $phongTro->tienNghi : [];
                    @endphp
                    @if (!empty($tienNghiArr))
                        <div class="flex flex-wrap gap-2">
                            @foreach ($tienNghiArr as $tnKey)
                                @php
                                    $tenTienNghi = $danhSachTienNghi[$tnKey]['ten'] ?? \App\Models\PhongTro::layTenTienNghi($tnKey);
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs bg-zinc-100 text-zinc-800 border border-zinc-200 font-medium">
                                    <span>{{ $tenTienNghi }}</span>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-zinc-400 italic">Chưa khai báo trang thiết bị tiện nghi cho phòng này.</p>
                    @endif
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
                                <span class="text-zinc-500 block">Người đại diện ký hợp đồng:</span>
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
                                <span class="text-zinc-500 block">Số người ở hiện tại:</span>
                                <span class="font-semibold text-sky-700">{{ $hd->soLuongNguoi ?? 1 }}</span>
                                <span class="text-zinc-500">/ tối đa {{ $phongTro->soNguoiToiDa }} người</span>
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

                        <div class="pt-2 text-right">
                            <a href="{{ route('hop-dong.chi-tiet', $hd->maHopDong) }}"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-sky-700 hover:text-sky-900 hover:underline">
                                <span>Xem hồ sơ hợp đồng chi tiết &rarr;</span>
                            </a>
                        </div>
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
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-lg w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-zinc-900">Chỉnh sửa thông tin phòng</h3>
                    <p class="text-[11px] text-zinc-500">Cập nhật đặc điểm, đơn giá và danh mục tiện nghi phòng</p>
                </div>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-base">&times;</button>
            </div>

            <form id="bieuMauSuaPhong" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4 text-xs">
                    <!-- KHỐI 1: ĐỊNH DANH & VỊ TRÍ -->
                    <div class="bg-zinc-50 p-3 rounded-lg border border-zinc-200 space-y-3">
                        <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider">1. Thông tin vị trí & Thể loại</div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="soPhongSua" class="block font-medium text-zinc-700 mb-1">Số phòng <span class="text-red-500">*</span></label>
                                <input type="text" name="soPhong" id="soPhongSua" required
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                            </div>

                            <div>
                                <label for="tangSua" class="block font-medium text-zinc-700 mb-1">Tầng / Vị trí</label>
                                <input type="text" name="tang" id="tangSua" placeholder="Ví dụ: Tầng 1, Tầng 2..."
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="maLoaiPhongSua" class="block font-medium text-zinc-700 mb-1">Thể loại phòng <span class="text-red-500">*</span></label>
                                <select name="maLoaiPhong" id="maLoaiPhongSua" required
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    @foreach ($danhSachLoaiPhong as $lp)
                                        <option value="{{ $lp->maLoaiPhong }}"
                                            data-don-gia="{{ $lp->donGiaGoiY }}"
                                            data-so-nguoi="{{ $lp->soNguoiToiDa }}"
                                            data-dien-tich="{{ $lp->dienTich }}">
                                            {{ $lp->tenLoaiPhong }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="dienTichSua" class="block font-medium text-zinc-700 mb-1">Diện tích thực tế (m²)</label>
                                <input type="number" step="0.1" name="dienTich" id="dienTichSua" placeholder="Ví dụ: 22"
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                            </div>
                        </div>
                    </div>

                    <!-- KHỐI 2: CHI PHÍ & SỨC CHỨA -->
                    <div class="bg-zinc-50 p-3 rounded-lg border border-zinc-200 space-y-3">
                        <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider">2. Chi phí & Sức chứa</div>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label for="donGiaSua" class="block font-medium text-zinc-700 mb-1">Đơn giá thuê (đ) <span class="text-red-500">*</span></label>
                                <input type="text" inputmode="numeric" name="donGiaMacDinh" id="donGiaSua" required placeholder="Ví dụ: 2.500.000"
                                    class="dinh-dang-tien w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                            </div>

                            <div>
                                <label for="tienCocSua" class="block font-medium text-zinc-700 mb-1">Tiền cọc gợi ý (đ)</label>
                                <input type="text" inputmode="numeric" name="tienCocMacDinh" id="tienCocSua" placeholder="Ví dụ: 2.500.000"
                                    class="dinh-dang-tien w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                            </div>

                            <div>
                                <label for="soNguoiToiDaSua" class="block font-medium text-zinc-700 mb-1">Sức chứa tối đa <span class="text-red-500">*</span></label>
                                <input type="number" name="soNguoiToiDa" id="soNguoiToiDaSua" min="1" max="20" required
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                            </div>
                        </div>
                    </div>

                    <!-- KHỐI 3: TIỆN NGHI NỘI THẤT -->
                    <div>
                        <label class="block font-medium text-zinc-700 mb-1.5">Tiện nghi & Nội thất sẵn có</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 bg-zinc-50 p-3 rounded-lg border border-zinc-200 max-h-36 overflow-y-auto">
                            @foreach ($danhSachTienNghi as $key => $tn)
                                <label class="flex items-center gap-2 p-1.5 rounded hover:bg-white cursor-pointer transition-colors text-xs">
                                    <input type="checkbox" name="tienNghi[]" value="{{ $key }}" id="tienNghiSua_{{ $key }}"
                                        class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900 hop-tien-nghi-sua">
                                    <span class="text-zinc-800 text-[11px] font-medium">{{ $tn['ten'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- KHỐI 4: GHI CHÚ -->
                    <div>
                        <label for="ghiChuSua" class="block font-medium text-zinc-700 mb-1">Ghi chú đặc điểm phòng</label>
                        <textarea name="ghiChu" id="ghiChuSua" rows="2"
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
</body>
</html>
