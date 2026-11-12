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
                        <a href="{{ route('hop-dong.danh-sach') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
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
                        data-ngay-sinh="{{ $khachThue->ngaySinh ? \Carbon\Carbon::parse($khachThue->ngaySinh)->format('Y-m-d') : '' }}"
                        data-gioi-tinh="{{ $khachThue->gioiTinh }}"
                        data-so-cccd="{{ $khachThue->soCCCD }}"
                        data-ngay-cap-cccd="{{ $khachThue->ngayCapCCCD ? \Carbon\Carbon::parse($khachThue->ngayCapCCCD)->format('Y-m-d') : '' }}"
                        data-noi-cap-cccd="{{ $khachThue->noiCapCCCD }}"
                        data-so-dien-thoai="{{ $khachThue->soDienThoai }}"
                        data-dia-chi="{{ $khachThue->diaChi }}"
                        data-nghe-nghiep="{{ $khachThue->ngheNghiep }}"
                        data-bien-so-xe="{{ $khachThue->bienSoXe }}"
                        data-nguoi-khan-cap="{{ $khachThue->nguoiLienHeKhanCap }}"
                        data-sdt-khan-cap="{{ $khachThue->sdtKhanCap }}"
                        data-quan-he-khan-cap="{{ $khachThue->quanHeKhanCap }}"
                        data-tam-tru="{{ $khachThue->daDangKyTamTru ? '1' : '0' }}"
                        data-la-dai-dien="{{ $thongTinPhong && $thongTinPhong['laDaiDien'] ? '1' : '0' }}"
                        data-ma-hop-dong-o-cung="{{ $thongTinPhong && !$thongTinPhong['laDaiDien'] && $thongTinPhong['hopDong'] ? $thongTinPhong['hopDong']->maHopDong : '' }}"
                        data-quan-he-o-cung="{{ $thongTinPhong && !$thongTinPhong['laDaiDien'] ? ($thongTinPhong['quanHe'] ?? '') : '' }}"
                        data-ten-phong="{{ $thongTinPhong && $thongTinPhong['phongTro'] ? $thongTinPhong['phongTro']->soPhong : '' }}"
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

            <!-- THẺ ĐỊNH DANH KHÁCH HÀNG -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs mb-6">
                <div class="flex items-start justify-between pb-4 border-b border-zinc-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-zinc-900 text-white flex items-center justify-center font-bold text-base shadow-xs">
                            {{ mb_substr($khachThue->hoTen, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-xl font-bold text-zinc-900">{{ $khachThue->hoTen }}</h2>
                                @if ($thongTinPhong && $thongTinPhong['phongTro'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-sky-50 text-sky-800 border border-sky-200">
                                        Phòng {{ $thongTinPhong['phongTro']->soPhong }}
                                    </span>
                                    @if ($thongTinPhong['laDaiDien'])
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            Đại diện hợp đồng
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                            Ở cùng phòng
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-zinc-100 text-zinc-600 border border-zinc-200">
                                        Chưa thuê phòng
                                    </span>
                                @endif

                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $khachThue->daDangKyTamTru ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                    {{ $khachThue->daDangKyTamTru ? '✓ Đã đăng ký tạm trú' : '! Chưa đăng ký tạm trú' }}
                                </span>
                            </div>
                            <div class="text-xs text-zinc-500 mt-1 flex items-center gap-2">
                                <span>Giới tính: <strong class="text-zinc-800">{{ $khachThue->gioiTinh === 'NU' ? 'Nữ' : ($khachThue->gioiTinh === 'KHAC' ? 'Khác' : 'Nam') }}</strong></span>
                                <span>&bull;</span>
                                <span>Ngày sinh: <strong class="text-zinc-800">{{ $khachThue->ngaySinh ? \Carbon\Carbon::parse($khachThue->ngaySinh)->format('d/m/Y') : 'Chưa cập nhật' }}</strong></span>
                                <span>&bull;</span>
                                <span>SĐT: <strong class="text-zinc-800 tabular-nums">{{ $khachThue->soDienThoai }}</strong></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        @if ($khachThue->nguoiDung)
                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                TK: {{ $khachThue->nguoiDung->email }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                Chưa có tài khoản
                            </span>
                        @endif
                    </div>
                </div>

                <!-- CÁC KHỐI THÔNG TIN CHI TIẾT -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="p-3 bg-zinc-50 rounded-lg border border-zinc-200">
                        <span class="font-semibold text-zinc-700 block mb-2 uppercase text-[11px] tracking-wider">Giấy tờ tùy thân (CCCD)</span>
                        <div class="space-y-1 text-zinc-600">
                            <div>Số CCCD: <strong class="text-zinc-900 tabular-nums">{{ $khachThue->soCCCD }}</strong></div>
                            <div>Ngày cấp: <span class="text-zinc-800">{{ $khachThue->ngayCapCCCD ? \Carbon\Carbon::parse($khachThue->ngayCapCCCD)->format('d/m/Y') : 'Chưa rõ' }}</span></div>
                            <div>Nơi cấp: <span class="text-zinc-800">{{ $khachThue->noiCapCCCD ?: 'Cục CSQLHC về TTXH' }}</span></div>
                        </div>
                    </div>

                    <div class="p-3 bg-zinc-50 rounded-lg border border-zinc-200">
                        <span class="font-semibold text-zinc-700 block mb-2 uppercase text-[11px] tracking-wider">Nghề nghiệp & Quê quán</span>
                        <div class="space-y-1 text-zinc-600">
                            <div>Nghề nghiệp: <strong class="text-zinc-900">{{ $khachThue->ngheNghiep ?: 'Chưa cập nhật' }}</strong></div>
                            <div>Thường trú: <span class="text-zinc-800">{{ $khachThue->diaChi ?: 'Chưa cập nhật thông tin địa chỉ thường trú.' }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- THÔNG TIN NƠI Ở HIỆN TẠI -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider">Thông tin nơi ở hiện tại</h3>
                    @if ($thongTinPhong && $thongTinPhong['phongTro'])
                        @if ($thongTinPhong['laDaiDien'])
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                Người đại diện hợp đồng
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                Người ở cùng phòng
                            </span>
                        @endif
                    @endif
                </div>

                @if ($thongTinPhong && $thongTinPhong['phongTro'])
                    @php
                        $hd = $thongTinPhong['hopDong'];
                        $phong = $thongTinPhong['phongTro'];
                    @endphp
                    <div class="rounded-lg border border-sky-200 bg-sky-50/40 p-4 text-xs space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-sky-600 text-white flex items-center justify-center font-bold text-sm">
                                    {{ $phong->soPhong }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-sky-950">Phòng {{ $phong->soPhong }} - {{ $phong->loaiPhong->tenLoaiPhong ?? 'Phòng trọ' }}</div>
                                    <div class="text-[11px] text-zinc-500">Vị trí: {{ $phong->tang ?: 'Tầng 1' }} | Sức chứa tối đa: {{ $phong->soNguoiToiDa }} người</div>
                                </div>
                            </div>

                            <a href="{{ route('phong-tro.chi-tiet', $phong->maPhong) }}"
                                class="px-2.5 py-1.5 rounded-lg border border-sky-200 bg-white hover:bg-sky-50 text-sky-700 text-xs font-medium transition-colors">
                                Xem phòng
                            </a>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-3 border-t border-sky-100">
                            <div>
                                <span class="text-zinc-500 block">Quy mô hiện tại:</span>
                                <strong class="text-zinc-900">{{ $hd->soLuongNguoi ?? 1 }} / {{ $phong->soNguoiToiDa }} người</strong>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Giá thuê chốt:</span>
                                <strong class="text-zinc-900 tabular-nums">{{ number_format($hd->giaThueChot, 0, ',', '.') }} đ</strong>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Tiền đặt cọc:</span>
                                <span class="text-zinc-800 tabular-nums">{{ number_format($hd->tienDatCoc, 0, ',', '.') }} đ</span>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Thời gian thuê:</span>
                                <span class="text-zinc-800">{{ \Carbon\Carbon::parse($hd->ngayBatDau)->format('d/m/Y') }} ~ {{ $hd->ngayKetThuc ? \Carbon\Carbon::parse($hd->ngayKetThuc)->format('d/m/Y') : 'Hiện tại' }}</span>
                            </div>
                        </div>

                        @if ($thongTinPhong['laDaiDien'])
                            <!-- DANH SÁCH BẠN CÙNG PHÒNG -->
                            <div class="pt-3 border-t border-sky-200/60">
                                <div class="text-xs font-semibold text-zinc-800 uppercase mb-2">
                                    Danh sách người ở cùng phòng ({{ $hd->danhSachNguoiOCung ? $hd->danhSachNguoiOCung->count() : 0 }} người):
                                </div>

                                @if ($hd->danhSachNguoiOCung && $hd->danhSachNguoiOCung->isNotEmpty())
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        @foreach ($hd->danhSachNguoiOCung as $noc)
                                            <div class="p-2.5 bg-white rounded-lg border border-zinc-200 flex items-center justify-between shadow-2xs">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-7 h-7 rounded-full bg-zinc-800 text-white flex items-center justify-center font-bold text-xs">
                                                        {{ mb_substr($noc->tenHienThi, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-zinc-900 flex items-center gap-1.5">
                                                            <span>{{ $noc->tenHienThi }}</span>
                                                            <span class="text-[10px] text-zinc-500 font-normal">({{ $noc->ghiChu ?: 'Ở cùng' }})</span>
                                                        </div>
                                                        <div class="text-[11px] text-zinc-500 tabular-nums">
                                                            SĐT: {{ $noc->sdtHienThi ?: '—' }} | CCCD: {{ $noc->cccdHienThi ?: '—' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-[11px] text-zinc-500 italic bg-white/60 p-2 rounded border border-zinc-200">
                                        Hiện tại chưa có người ở cùng nào được thêm vào phòng này.
                                    </p>
                                @endif
                            </div>
                        @else
                            <!-- THÔNG TIN NGƯỜI ĐẠI DIỆN HỢP ĐỒNG KHI LÀ NGƯỜI Ở CÙNG -->
                            <div class="pt-3 border-t border-sky-200/60 p-3 bg-white/80 rounded-lg border border-zinc-200 space-y-1.5">
                                <div class="text-xs font-semibold text-zinc-800 uppercase mb-1">Thông tin đại diện hợp đồng</div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-indigo-700 text-white flex items-center justify-center font-bold text-xs">
                                            {{ mb_substr($thongTinPhong['nguoiDaiDien']->hoTen ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-zinc-900">
                                                <a href="{{ route('khach-thue.chi-tiet', $thongTinPhong['nguoiDaiDien']->maKhach ?? 0) }}" class="hover:underline hover:text-indigo-700">
                                                    {{ $thongTinPhong['nguoiDaiDien']->hoTen ?? 'Chưa rõ' }}
                                                </a>
                                            </div>
                                            <div class="text-[11px] text-zinc-500">
                                                SĐT: {{ $thongTinPhong['nguoiDaiDien']->soDienThoai ?? 'Chưa có' }} | Quan hệ với bạn: <strong class="text-zinc-800">{{ $thongTinPhong['quanHe'] ?: 'Bạn cùng phòng' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        Đứng tên ký hợp đồng
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class="pt-2 text-right">
                            <a href="{{ route('hop-dong.chi-tiet', $hd->maHopDong) }}"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-sky-700 hover:text-sky-900 hover:underline">
                                <span>Xem hợp đồng chi tiết #HD{{ $hd->maHopDong }} &rarr;</span>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center text-xs text-zinc-600 bg-zinc-50 rounded-lg border border-zinc-200 space-y-3">
                        <p>Khách hiện chưa đứng tên hợp đồng hoặc ở cùng phòng nào.</p>
                        <div class="flex flex-wrap items-center justify-center gap-2">
                            <a href="{{ route('hop-dong.danh-sach') }}"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors">
                                <span>Bước 3: Ký hợp đồng mới (Đại diện)</span>
                            </a>
                            <a href="{{ route('khach-o-cung.danh-sach') }}"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-100 transition-colors">
                                <span>Bước 4: Thêm vào ở cùng phòng đang thuê</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- LỊCH SỬ THUÊ TRỌ -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                <h3 class="text-sm font-semibold text-zinc-900 mb-4 uppercase tracking-wider">Lịch sử các hợp đồng thuê</h3>

                @if ($khachThue->hopDong && $khachThue->hopDong->count() > 0)
                    <div class="overflow-hidden rounded-lg border border-zinc-200">
                        <table class="min-w-full divide-y divide-zinc-200 text-xs">
                            <thead class="bg-zinc-50 text-zinc-500 text-left font-medium">
                                <tr>
                                    <th class="px-4 py-2.5">Mã HĐ</th>
                                    <th class="px-4 py-2.5">Phòng</th>
                                    <th class="px-4 py-2.5">Thời gian thuê</th>
                                    <th class="px-4 py-2.5 text-right">Giá thuê</th>
                                    <th class="px-4 py-2.5 text-right">Tiền cọc</th>
                                    <th class="px-4 py-2.5 text-center">Trạng thái</th>
                                    <th class="px-4 py-2.5 text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 bg-white">
                                @foreach ($khachThue->hopDong as $hd)
                                    <tr>
                                        <td class="px-4 py-2.5 font-medium text-zinc-900">#HD{{ $hd->maHopDong }}</td>
                                        <td class="px-4 py-2.5 font-semibold text-zinc-900">{{ $hd->phongTro->soPhong ?? 'P.?' }}</td>
                                        <td class="px-4 py-2.5 text-zinc-600">
                                            {{ \Carbon\Carbon::parse($hd->ngayBatDau)->format('d/m/Y') }} ~ {{ $hd->ngayKetThuc ? \Carbon\Carbon::parse($hd->ngayKetThuc)->format('d/m/Y') : 'Đang thuê' }}
                                        </td>
                                        <td class="px-4 py-2.5 text-right font-medium text-zinc-900 tabular-nums">{{ number_format($hd->giaThueChot, 0, ',', '.') }} đ</td>
                                        <td class="px-4 py-2.5 text-right text-zinc-700 tabular-nums">{{ number_format($hd->tienDatCoc, 0, ',', '.') }} đ</td>
                                        <td class="px-4 py-2.5 text-center">
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
                                        <td class="px-4 py-2.5 text-right">
                                            <a href="{{ route('hop-dong.chi-tiet', $hd->maHopDong) }}"
                                                class="text-zinc-700 hover:text-zinc-900 font-medium hover:underline">
                                                Xem
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-xs text-zinc-500 bg-zinc-50 rounded-lg border border-zinc-200">
                        Chưa có lịch sử hợp đồng nào của khách thuê này.
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- MODAL SỬA KHÁCH THUÊ -->
    <div id="modalSuaKhach" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-lg w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-zinc-900">Cập nhật hồ sơ khách thuê</h3>
                    <p class="text-[11px] text-zinc-500">Chỉnh sửa thông tin nhân thân, tạm trú và liên hệ</p>
                </div>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-base">&times;</button>
            </div>

            <form id="bieuMauSuaKhach" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4 text-xs">
                    <!-- KHỐI THÔNG TIN CÁ NHÂN & CCCD (GỘP CHUNG) -->
                    <div class="bg-zinc-50 p-3.5 rounded-lg border border-zinc-200 space-y-3">
                        <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider">Thông tin cá nhân & CCCD</div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="hoTenSua" class="block font-medium text-zinc-700 mb-1">Họ và tên <span class="text-red-500">*</span></label>
                                <input type="text" name="hoTen" id="hoTenSua" required
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                            </div>

                            <div>
                                <label for="gioiTinhSua" class="block font-medium text-zinc-700 mb-1">Giới tính <span class="text-red-500">*</span></label>
                                <select name="gioiTinh" id="gioiTinhSua" required
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    <option value="NAM">Nam</option>
                                    <option value="NU">Nữ</option>
                                    <option value="KHAC">Khác</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="ngaySinhSua" class="block font-medium text-zinc-700 mb-1">Ngày sinh</label>
                                <input type="date" name="ngaySinh" id="ngaySinhSua"
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                            </div>

                            <div>
                                <label for="soDienThoaiSua" class="block font-medium text-zinc-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                                <input type="text" name="soDienThoai" id="soDienThoaiSua" required
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="ngheNghiepSua" class="block font-medium text-zinc-700 mb-1">Nghề nghiệp / Đơn vị</label>
                                <input type="text" name="ngheNghiep" id="ngheNghiepSua" placeholder="Ví dụ: Kỹ sư, Sinh viên..."
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                            </div>

                            <div>
                                <label for="diaChiSua" class="block font-medium text-zinc-700 mb-1">Địa chỉ thường trú / Quê quán</label>
                                <input type="text" name="diaChi" id="diaChiSua"
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-2 border-t border-zinc-200">
                            <div>
                                <label for="soCCCDSua" class="block font-medium text-zinc-700 mb-1">Số CCCD (12 số) <span class="text-red-500">*</span></label>
                                <input type="text" name="soCCCD" id="soCCCDSua" required maxlength="12"
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                            </div>

                            <div>
                                <label for="ngayCapCCCDSua" class="block font-medium text-zinc-700 mb-1">Ngày cấp thẻ</label>
                                <input type="date" name="ngayCapCCCD" id="ngayCapCCCDSua"
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="noiCapCCCDSua" class="block font-medium text-zinc-700 mb-1">Nơi cấp CCCD</label>
                                <input type="text" name="noiCapCCCD" id="noiCapCCCDSua"
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                            </div>

                            <div class="flex items-center pt-5">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="daDangKyTamTru" id="daDangKyTamTruSua" value="1"
                                        class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                                    <span class="font-medium text-zinc-800">Đã đăng ký tạm trú</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2 pt-4 border-t border-zinc-100">
                    <button type="button" data-dong-modal
                        class="px-3.5 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                        Cập nhật hồ sơ
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
