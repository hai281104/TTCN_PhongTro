<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Phòng Trọ - Hệ Thống Quản Lý Nhà Trọ</title>
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

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if (session('thongBaoThanhCong'))
                <div class="hop-thong-bao mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-xs flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('thongBaoThanhCong') }}</span>
                    </div>
                    <button type="button" onclick="this.closest('.hop-thong-bao').remove()" class="text-emerald-600 hover:text-emerald-900 text-sm font-semibold">&times;</button>
                </div>
            @endif

            @if (session('thongBaoLoi'))
                <div class="hop-thong-bao mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 shadow-xs flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('thongBaoLoi') }}</span>
                    </div>
                    <button type="button" onclick="this.closest('.hop-thong-bao').remove()" class="text-red-600 hover:text-red-900 text-sm font-semibold">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="hop-thong-bao mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 shadow-xs">
                    <div class="flex items-center gap-2 mb-2 font-medium text-sm">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span>Vui lòng kiểm tra lại thông tin:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach ($errors->all() as $loi)
                            <li>{{ $loi }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- THẺ THỐNG KÊ (KPIs) -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <span class="text-xs font-medium text-zinc-500 block mb-1">Tổng số phòng</span>
                    <div class="text-2xl font-bold text-zinc-900 tabular-nums">{{ $tongSoPhong }}</div>
                </div>
                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <span class="text-xs font-medium text-zinc-500 block mb-1">Đang thuê</span>
                    <div class="text-2xl font-bold text-sky-700 tabular-nums">{{ $soPhongDangThue }}</div>
                </div>
                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <span class="text-xs font-medium text-zinc-500 block mb-1">Phòng trống</span>
                    <div class="text-2xl font-bold text-emerald-700 tabular-nums">{{ $soPhongTrong }}</div>
                </div>
                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <span class="text-xs font-medium text-zinc-500 block mb-1">Đang sửa chữa</span>
                    <div class="text-2xl font-bold text-amber-700 tabular-nums">{{ $soPhongDangSuaChua }}</div>
                </div>
                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs col-span-2 md:col-span-1">
                    <span class="text-xs font-medium text-zinc-500 block mb-1">Tỷ lệ lấp đầy</span>
                    <div class="text-2xl font-bold text-zinc-900 tabular-nums">{{ $tyLeLapDay }}%</div>
                </div>
            </div>

            <!-- THANH TÌM KIẾM, BỘ LỌC VÀ NÚT TẠO PHÒNG -->
            <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <form action="{{ route('phong-tro.danh-sach') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
                        <div class="relative min-w-[170px] flex-1 sm:flex-none">
                            <input type="text" name="tuKhoa" value="{{ request('tuKhoa') }}"
                                placeholder="Tìm theo số phòng..."
                                class="w-full pl-9 pr-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-all">
                            <svg class="w-4 h-4 text-zinc-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <select name="maLoaiPhong"
                            class="px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-all">
                            <option value="">Tất cả thể loại</option>
                            @foreach ($danhSachLoaiPhong as $lp)
                                <option value="{{ $lp->maLoaiPhong }}" {{ request('maLoaiPhong') == $lp->maLoaiPhong ? 'selected' : '' }}>
                                    {{ $lp->tenLoaiPhong }}
                                </option>
                            @endforeach
                        </select>

                        @if ($danhSachTang->isNotEmpty())
                            <select name="tang"
                                class="px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-all">
                                <option value="">Tất cả các tầng</option>
                                @foreach ($danhSachTang as $t)
                                    <option value="{{ $t }}" {{ request('tang') === (string)$t ? 'selected' : '' }}>{{ is_numeric($t) ? 'Tầng ' . $t : $t }}</option>
                                @endforeach
                            </select>
                        @endif

                        <select name="trangThaiPhong"
                            class="px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-all">
                            <option value="">Tất cả trạng thái</option>
                            <option value="TRONG" {{ request('trangThaiPhong') === 'TRONG' ? 'selected' : '' }}>Trống</option>
                            <option value="DANG_THUE" {{ request('trangThaiPhong') === 'DANG_THUE' ? 'selected' : '' }}>Đang thuê</option>
                            <option value="DANG_SUA_CHUA" {{ request('trangThaiPhong') === 'DANG_SUA_CHUA' ? 'selected' : '' }}>Đang sửa chữa</option>
                        </select>

                        <div class="flex items-center gap-2">
                            <input type="text" inputmode="numeric" name="giaTu" value="{{ request('giaTu') ? number_format(str_replace(['.', ','], '', request('giaTu')), 0, ',', '.') : '' }}" placeholder="Giá từ..."
                                class="dinh-dang-tien w-28 px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                            <span class="text-zinc-400 text-xs">-</span>
                            <input type="text" inputmode="numeric" name="giaDen" value="{{ request('giaDen') ? number_format(str_replace(['.', ','], '', request('giaDen')), 0, ',', '.') : '' }}" placeholder="Đến..."
                                class="dinh-dang-tien w-28 px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                        </div>

                        <button type="submit"
                            class="px-3.5 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                            Lọc
                        </button>

                        @if (request()->hasAny(['tuKhoa', 'maLoaiPhong', 'tang', 'trangThaiPhong', 'giaTu', 'giaDen']))
                            <a href="{{ route('phong-tro.danh-sach') }}"
                                class="px-3 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-600 text-xs font-medium hover:bg-zinc-100 transition-colors">
                                Xóa bộ lọc
                            </a>
                        @endif
                    </form>

                    <button type="button" id="nutMoThemPhong"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Thêm phòng mới</span>
                    </button>
                </div>
            </div>

            @if ($danhSachPhong->isEmpty())
                <div class="bg-white border border-zinc-200 rounded-xl p-12 text-center shadow-xs">
                    <svg class="w-12 h-12 text-zinc-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="text-sm font-semibold text-zinc-900 mb-1">Không tìm thấy phòng trọ nào</h3>
                    <p class="text-xs text-zinc-500 mb-4">Thử thay đổi bộ lọc tìm kiếm hoặc thêm phòng mới vào hệ thống.</p>
                </div>
            @else
                <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden shadow-xs mb-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 text-xs">
                            <thead class="bg-zinc-50 text-zinc-500 uppercase tracking-wider font-medium text-[11px]">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left">Phòng & Vị trí</th>
                                    <th scope="col" class="px-4 py-3 text-left">Thể loại & Diện tích</th>
                                    <th scope="col" class="px-4 py-3 text-left">Tiện nghi nội thất</th>
                                    <th scope="col" class="px-4 py-3 text-center">Sức chứa / Ở</th>
                                    <th scope="col" class="px-4 py-3 text-left">Trạng thái</th>
                                    <th scope="col" class="px-4 py-3 text-right">Giá thuê & Tiền cọc</th>
                                    <th scope="col" class="px-4 py-3 text-left">Người thuê</th>
                                    <th scope="col" class="px-4 py-3 text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 bg-white">
                                @foreach ($danhSachPhong as $phong)
                                    <tr class="hover:bg-zinc-50/80 transition-colors">
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-zinc-100 flex items-center justify-center font-bold text-xs text-zinc-700 border border-zinc-200/80">
                                                    P
                                                </div>
                                                <div>
                                                    <a href="{{ route('phong-tro.chi-tiet', $phong->maPhong) }}" class="font-semibold text-zinc-900 hover:text-zinc-600 hover:underline">
                                                        {{ $phong->soPhong }}
                                                    </a>
                                                    <div class="flex items-center gap-1.5 mt-0.5">
                                                        <span class="text-[10px] text-zinc-500 bg-zinc-100 px-1.5 py-0.2 rounded border border-zinc-200">
                                                            {{ $phong->tang ?: 'Tầng 1' }}
                                                        </span>
                                                        <span class="text-[10px] text-zinc-400">#{{ $phong->maPhong }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <div class="font-medium text-zinc-800">
                                                {{ $phong->loaiPhong->tenLoaiPhong ?? 'Chưa phân loại' }}
                                            </div>
                                            <div class="text-[11px] text-zinc-500">
                                                {{ $phong->dienTich ? $phong->dienTich . ' m²' : (optional($phong->loaiPhong)->dienTich ? optional($phong->loaiPhong)->dienTich . ' m² (chuẩn)' : '—') }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-3.5">
                                            @php
                                                $tienNghiArr = is_array($phong->tienNghi) ? $phong->tienNghi : [];
                                            @endphp
                                            @if (!empty($tienNghiArr))
                                                <div class="flex flex-wrap gap-1 max-w-[200px]">
                                                    @foreach (array_slice($tienNghiArr, 0, 4) as $tnKey)
                                                        @if (isset($danhSachTienNghi[$tnKey]))
                                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] bg-zinc-100 text-zinc-700 border border-zinc-200" title="{{ $danhSachTienNghi[$tnKey]['ten'] }}">
                                                                <span>{{ $danhSachTienNghi[$tnKey]['bieuTuong'] }}</span>
                                                                <span class="truncate max-w-[70px]">{{ $danhSachTienNghi[$tnKey]['ten'] }}</span>
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                    @if (count($tienNghiArr) > 4)
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] bg-zinc-100 text-zinc-500 font-medium">
                                                            +{{ count($tienNghiArr) - 4 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-zinc-400 italic text-[11px]">Cơ bản</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                            @if ($phong->trangThaiPhong === 'DANG_THUE' && $phong->hopDongHieuLuc)
                                                <span class="font-semibold text-sky-700 tabular-nums">
                                                    {{ $phong->hopDongHieuLuc->soLuongNguoi ?? 1 }}
                                                </span>
                                                <span class="text-zinc-400">/ {{ $phong->soNguoiToiDa }} người</span>
                                            @else
                                                <span class="text-zinc-600 tabular-nums">
                                                    Tối đa {{ $phong->soNguoiToiDa }} người
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            @if ($phong->trangThaiPhong === 'TRONG')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Trống
                                                </span>
                                            @elseif ($phong->trangThaiPhong === 'DANG_THUE')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-sky-50 text-sky-700 border border-sky-200">
                                                    Đang thuê
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                    Đang sửa chữa
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3.5 whitespace-nowrap text-right">
                                            <div class="font-semibold text-zinc-900 tabular-nums">
                                                {{ number_format($phong->donGiaMacDinh, 0, ',', '.') }} đ
                                            </div>
                                            <div class="text-[11px] text-zinc-500 tabular-nums">
                                                Cọc: {{ number_format($phong->tienCocMacDinh ?: $phong->donGiaMacDinh, 0, ',', '.') }} đ
                                            </div>
                                        </td>

                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            @if ($phong->hopDongHieuLuc && $phong->hopDongHieuLuc->khachThue)
                                                <div class="flex flex-col">
                                                    <span class="font-medium text-sky-700" title="{{ $phong->hopDongHieuLuc->khachThue->hoTen }}">
                                                        {{ $phong->hopDongHieuLuc->khachThue->hoTen }}
                                                    </span>
                                                    <span class="text-[11px] text-zinc-400 tabular-nums">
                                                        {{ $phong->hopDongHieuLuc->khachThue->soDienThoai }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-zinc-400 italic">Chưa có người thuê</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3.5 whitespace-nowrap text-right">
                                            <div class="inline-flex items-center justify-end gap-1.5">
                                                @if ($phong->trangThaiPhong === 'TRONG')
                                                    <a href="{{ route('hop-dong.danh-sach', ['maPhongChon' => $phong->maPhong]) }}"
                                                        class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-medium transition-colors shadow-2xs"
                                                        title="Lập hợp đồng cho thuê phòng này ngay">
                                                        Cho thuê
                                                    </a>
                                                @endif

                                                <button type="button" data-hanh-dong="xem-chi-tiet" data-ma-phong="{{ $phong->maPhong }}"
                                                    class="px-2.5 py-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-[11px] font-medium transition-colors">
                                                    Chi tiết
                                                </button>

                                                <button type="button" data-hanh-dong="sua-phong"
                                                    data-ma-phong="{{ $phong->maPhong }}"
                                                    data-so-phong="{{ $phong->soPhong }}"
                                                    data-ma-loai-phong="{{ $phong->maLoaiPhong }}"
                                                    data-tang="{{ $phong->tang }}"
                                                    data-dien-tich="{{ $phong->dienTich }}"
                                                    data-tien-coc="{{ $phong->tienCocMacDinh }}"
                                                    data-tien-nghi="{{ json_encode($phong->tienNghi ?: []) }}"
                                                    data-so-nguoi-toi-da="{{ $phong->soNguoiToiDa }}"
                                                    data-don-gia="{{ $phong->donGiaMacDinh }}"
                                                    data-ghi-chu="{{ $phong->ghiChu }}"
                                                    class="px-2.5 py-1.5 rounded-lg border border-zinc-200 hover:bg-zinc-50 text-zinc-700 text-[11px] font-medium transition-colors">
                                                    Sửa
                                                </button>

                                                @if ($phong->trangThaiPhong !== 'DANG_THUE')
                                                    <form action="{{ route('phong-tro.doi-trang-thai', $phong->maPhong) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="px-2.5 py-1.5 rounded-lg border border-zinc-200 hover:bg-zinc-50 text-zinc-600 text-[11px] font-medium transition-colors"
                                                            title="{{ $phong->trangThaiPhong === 'TRONG' ? 'Chuyển sang sửa chữa' : 'Chuyển sang sẵn sàng đón khách' }}">
                                                            {{ $phong->trangThaiPhong === 'TRONG' ? 'Bảo trì' : 'Mở lại' }}
                                                        </button>
                                                    </form>

                                                    <button type="button" data-hanh-dong="xoa-phong"
                                                        data-ma-phong="{{ $phong->maPhong }}"
                                                        data-so-phong="{{ $phong->soPhong }}"
                                                        class="px-2.5 py-1.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 text-[11px] font-medium transition-colors">
                                                        Xóa
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    {{ $danhSachPhong->links() }}
                </div>
            @endif
        </main>
    </div>

    <!-- MODAL THÊM PHÒNG (TOÀN MÀN HÌNH RỘNG RÃI) -->
    <div id="modalThemPhong" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/60 backdrop-blur-xs items-center justify-center p-3 sm:p-5 md:p-8">
        <div class="chuyen-dong-modal bg-white rounded-2xl border border-zinc-200 shadow-2xl max-w-5xl xl:max-w-6xl w-full max-h-[94vh] flex flex-col overflow-hidden">
            <!-- Header Modal -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 bg-zinc-50/70 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                        +
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900">Thêm phòng trọ mới</h3>
                        <p class="text-[11px] text-zinc-500">Khai báo thông số phòng, đơn giá và danh mục tiện nghi nội thất</p>
                    </div>
                </div>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 p-1.5 rounded-lg hover:bg-zinc-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form Body (Chia 2 Cột Song Song) -->
            <form action="{{ route('phong-tro.tao-moi') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 overflow-y-auto flex-1 text-xs">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- CỘT TRÁI: VỊ TRÍ, THỂ LOẠI & TÀI CHÍNH -->
                        <div class="space-y-4">
                            <!-- KHỐI 1: ĐỊNH DANH & VỊ TRÍ -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3.5">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                    <span>1. Thông tin vị trí & Thể loại</span>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="soPhongThem" class="block font-medium text-zinc-700 mb-1">Số phòng <span class="text-red-500">*</span></label>
                                        <input type="text" name="soPhong" id="soPhongThem" required placeholder="Ví dụ: P.101, P.202..."
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>

                                    <div>
                                        <label for="tangThem" class="block font-medium text-zinc-700 mb-1">Tầng / Vị trí</label>
                                        <input type="text" name="tang" id="tangThem" placeholder="Ví dụ: Tầng 1, Tầng 2, Trệt..." value="Tầng 1"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="maLoaiPhongThem" class="block font-medium text-zinc-700 mb-1">Thể loại phòng <span class="text-red-500">*</span></label>
                                        <select name="maLoaiPhong" id="maLoaiPhongThem" required
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                            <option value="">-- Chọn thể loại --</option>
                                            @foreach ($danhSachLoaiPhong as $lp)
                                                <option value="{{ $lp->maLoaiPhong }}"
                                                    data-don-gia="{{ $lp->donGiaGoiY }}"
                                                    data-so-nguoi="{{ $lp->soNguoiToiDa }}"
                                                    data-dien-tich="{{ $lp->dienTich }}">
                                                    {{ $lp->tenLoaiPhong }} (Tối đa {{ $lp->soNguoiToiDa }} người)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="dienTichThem" class="block font-medium text-zinc-700 mb-1">Diện tích thực tế (m²)</label>
                                        <input type="number" step="0.1" name="dienTich" id="dienTichThem" placeholder="Ví dụ: 22"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                                    </div>
                                </div>
                            </div>

                            <!-- KHỐI 2: CHI PHÍ & SỨC CHỨA -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3.5">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                    <span>2. Chi phí thuê & Sức chứa</span>
                                </div>

                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label for="donGiaThem" class="block font-medium text-zinc-700 mb-1">Đơn giá thuê (đ) <span class="text-red-500">*</span></label>
                                        <input type="text" inputmode="numeric" name="donGiaMacDinh" id="donGiaThem" required placeholder="Ví dụ: 2.500.000"
                                            value="{{ old('donGiaMacDinh') ? number_format(str_replace(['.', ','], '', old('donGiaMacDinh')), 0, ',', '.') : '' }}"
                                            class="dinh-dang-tien w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                                    </div>

                                    <div>
                                        <label for="tienCocThem" class="block font-medium text-zinc-700 mb-1">Tiền cọc gợi ý (đ)</label>
                                        <input type="text" inputmode="numeric" name="tienCocMacDinh" id="tienCocThem" placeholder="Ví dụ: 2.500.000"
                                            class="dinh-dang-tien w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                                    </div>

                                    <div>
                                        <label for="soNguoiToiDaThem" class="block font-medium text-zinc-700 mb-1">Sức chứa tối đa <span class="text-red-500">*</span></label>
                                        <input type="number" name="soNguoiToiDa" id="soNguoiToiDaThem" min="1" max="20" required value="2"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CỘT PHẢI: TIỆN NGHI NỘI THẤT & GHI CHÚ -->
                        <div class="space-y-4 flex flex-col justify-between">
                            <!-- KHỐI 3: TIỆN NGHI NỘI THẤT -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                        <span>3. Tiện nghi & Nội thất sẵn có</span>
                                    </span>
                                    <span class="text-[10px] text-zinc-500 font-normal">Chọn những tiện nghi đã trang bị sẵn</span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 bg-white p-3 rounded-lg border border-zinc-200">
                                    @foreach ($danhSachTienNghi as $key => $tn)
                                        <label class="flex items-center gap-2.5 p-2 rounded-lg border border-zinc-100 hover:border-zinc-300 hover:bg-zinc-50/50 cursor-pointer transition-all text-xs">
                                            <input type="checkbox" name="tienNghi[]" value="{{ $key }}"
                                                class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                                            <span class="text-sm shrink-0">{{ $tn['bieuTuong'] }}</span>
                                            <span class="text-zinc-800 text-[11px] font-medium">{{ $tn['ten'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- KHỐI 4: GHI CHÚ -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-2">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                    <span>4. Ghi chú đặc điểm phòng</span>
                                </div>
                                <textarea name="ghiChu" id="ghiChuThem" rows="3" placeholder="Ví dụ: Gần cầu thang, có ban công thoáng mát, cửa khóa vân tay riêng biệt..."
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal (Nút Lưu Cố Định) -->
                <div class="px-6 py-4 bg-zinc-50 border-t border-zinc-200 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" data-dong-modal
                        class="px-4 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-100 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                        Lưu phòng mới
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SỬA PHÒNG (TOÀN MÀN HÌNH RỘNG RÃI) -->
    <div id="modalSuaPhong" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/60 backdrop-blur-xs items-center justify-center p-3 sm:p-5 md:p-8">
        <div class="chuyen-dong-modal bg-white rounded-2xl border border-zinc-200 shadow-2xl max-w-5xl xl:max-w-6xl w-full max-h-[94vh] flex flex-col overflow-hidden">
            <!-- Header Modal -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 bg-zinc-50/70 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                        ✎
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900">Chỉnh sửa thông tin phòng</h3>
                        <p class="text-[11px] text-zinc-500">Cập nhật đặc điểm, đơn giá và danh mục tiện nghi phòng</p>
                    </div>
                </div>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 p-1.5 rounded-lg hover:bg-zinc-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form Body (Chia 2 Cột Song Song) -->
            <form id="bieuMauSuaPhong" action="" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                @method('PUT')
                <div class="p-6 overflow-y-auto flex-1 text-xs">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- CỘT TRÁI: VỊ TRÍ, THỂ LOẠI & TÀI CHÍNH -->
                        <div class="space-y-4">
                            <!-- KHỐI 1: ĐỊNH DANH & VỊ TRÍ -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3.5">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                    <span>1. Thông tin vị trí & Thể loại</span>
                                </div>

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
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3.5">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                    <span>2. Chi phí thuê & Sức chứa</span>
                                </div>

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
                        </div>

                        <!-- CỘT PHẢI: TIỆN NGHI NỘI THẤT & GHI CHÚ -->
                        <div class="space-y-4 flex flex-col justify-between">
                            <!-- KHỐI 3: TIỆN NGHI NỘI THẤT -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                        <span>3. Tiện nghi & Nội thất sẵn có</span>
                                    </span>
                                    <span class="text-[10px] text-zinc-500 font-normal">Chọn những tiện nghi đã trang bị sẵn</span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 bg-white p-3 rounded-lg border border-zinc-200">
                                    @foreach ($danhSachTienNghi as $key => $tn)
                                        <label class="flex items-center gap-2.5 p-2 rounded-lg border border-zinc-100 hover:border-zinc-300 hover:bg-zinc-50/50 cursor-pointer transition-all text-xs">
                                            <input type="checkbox" name="tienNghi[]" value="{{ $key }}" id="tienNghiSua_{{ $key }}"
                                                class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900 hop-tien-nghi-sua">
                                            <span class="text-sm shrink-0">{{ $tn['bieuTuong'] }}</span>
                                            <span class="text-zinc-800 text-[11px] font-medium">{{ $tn['ten'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- KHỐI 4: GHI CHÚ -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-2">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                    <span>4. Ghi chú đặc điểm phòng</span>
                                </div>
                                <textarea name="ghiChu" id="ghiChuSua" rows="3"
                                    class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal (Nút Lưu Cố Định) -->
                <div class="px-6 py-4 bg-zinc-50 border-t border-zinc-200 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" data-dong-modal
                        class="px-4 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-100 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                        Cập nhật phòng
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CHI TIẾT PHÒNG -->
    <div id="modalChiTietPhong" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-lg w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <h3 id="chiTietSoPhong" class="text-sm font-semibold text-zinc-900">Chi tiết phòng</h3>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-base">&times;</button>
            </div>

            <div id="noiDungChiTietPhong">
                <!-- Nội dung được nạp qua JavaScript -->
            </div>

            <div class="mt-6 pt-4 border-t border-zinc-100 flex justify-end">
                <button type="button" data-dong-modal
                    class="px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                    Đóng
                </button>
            </div>
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
