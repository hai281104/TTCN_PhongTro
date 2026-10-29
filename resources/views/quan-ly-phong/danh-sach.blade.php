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
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <div class="text-[11px] font-medium text-zinc-500 uppercase tracking-wider">Tổng số phòng</div>
                    <div class="text-2xl font-bold text-zinc-900 mt-1 tabular-nums">{{ $tongSoPhong }}</div>
                </div>

                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div class="text-[11px] font-medium text-zinc-500 uppercase tracking-wider">Đang thuê</div>
                        <span class="text-[11px] font-medium text-sky-700 bg-sky-50 px-1.5 py-0.5 rounded border border-sky-200">{{ $tyLeLapDay }}%</span>
                    </div>
                    <div class="text-2xl font-bold text-sky-700 mt-1 tabular-nums">{{ $soPhongDangThue }}</div>
                </div>

                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <div class="text-[11px] font-medium text-zinc-500 uppercase tracking-wider">Phòng trống</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-1 tabular-nums">{{ $soPhongTrong }}</div>
                </div>

                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <div class="text-[11px] font-medium text-zinc-500 uppercase tracking-wider">Đang sửa chữa</div>
                    <div class="text-2xl font-bold text-amber-700 mt-1 tabular-nums">{{ $soPhongDangSuaChua }}</div>
                </div>
            </div>

            <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <form action="{{ route('phong-tro.danh-sach') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
                        <div class="relative w-full sm:w-64">
                            <input type="text" name="tuKhoa" value="{{ request('tuKhoa') }}"
                                placeholder="Tìm theo số phòng..."
                                class="w-full pl-9 pr-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-all">
                            <svg class="w-4 h-4 text-zinc-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <select name="trangThaiPhong"
                            class="px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-all">
                            <option value="">Tất cả trạng thái</option>
                            <option value="TRONG" {{ request('trangThaiPhong') === 'TRONG' ? 'selected' : '' }}>Trống</option>
                            <option value="DANG_THUE" {{ request('trangThaiPhong') === 'DANG_THUE' ? 'selected' : '' }}>Đang thuê</option>
                            <option value="DANG_SUA_CHUA" {{ request('trangThaiPhong') === 'DANG_SUA_CHUA' ? 'selected' : '' }}>Đang sửa chữa</option>
                        </select>

                        <div class="flex items-center gap-2">
                            <input type="number" name="giaTu" value="{{ request('giaTu') }}" placeholder="Giá từ..."
                                class="w-28 px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                            <span class="text-zinc-400 text-xs">-</span>
                            <input type="number" name="giaDen" value="{{ request('giaDen') }}" placeholder="Đến..."
                                class="w-28 px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                        </div>

                        <button type="submit"
                            class="px-3.5 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                            Lọc
                        </button>

                        @if (request()->hasAny(['tuKhoa', 'trangThaiPhong', 'giaTu', 'giaDen']))
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
                                    <th scope="col" class="px-4 py-3 text-left">Số phòng</th>
                                    <th scope="col" class="px-4 py-3 text-left">Trạng thái</th>
                                    <th scope="col" class="px-4 py-3 text-right">Đơn giá niêm yết</th>
                                    <th scope="col" class="px-4 py-3 text-left">Khách đang thuê</th>
                                    <th scope="col" class="px-4 py-3 text-left">Ghi chú</th>
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
                                                    <div class="text-[10px] text-zinc-400">Mã: #{{ $phong->maPhong }}</div>
                                                </div>
                                            </div>
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
                                            <span class="font-semibold text-zinc-900 tabular-nums">
                                                {{ number_format($phong->donGiaMacDinh, 0, ',', '.') }} đ
                                            </span>
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

                                        <td class="px-4 py-3.5 text-zinc-600 max-w-xs truncate" title="{{ $phong->ghiChu }}">
                                            {{ $phong->ghiChu ?: '—' }}
                                        </td>

                                        <td class="px-4 py-3.5 whitespace-nowrap text-right">
                                            <div class="inline-flex items-center justify-end gap-1.5">
                                                <button type="button" data-hanh-dong="xem-chi-tiet" data-ma-phong="{{ $phong->maPhong }}"
                                                    class="px-2.5 py-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-[11px] font-medium transition-colors">
                                                    Chi tiết
                                                </button>

                                                <button type="button" data-hanh-dong="sua-phong"
                                                    data-ma-phong="{{ $phong->maPhong }}"
                                                    data-so-phong="{{ $phong->soPhong }}"
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

    <!-- MODAL THÊM PHÒNG -->
    <div id="modalThemPhong" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <h3 class="text-sm font-semibold text-zinc-900">Thêm phòng trọ mới</h3>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-base">&times;</button>
            </div>

            <form action="{{ route('phong-tro.tao-moi') }}" method="POST">
                @csrf
                <div class="space-y-4 text-xs">
                    <div>
                        <label for="soPhongThem" class="block font-medium text-zinc-700 mb-1">Số phòng <span class="text-red-500">*</span></label>
                        <input type="text" name="soPhong" id="soPhongThem" required placeholder="Ví dụ: P.101, P.202..."
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>

                    <div>
                        <label for="donGiaThem" class="block font-medium text-zinc-700 mb-1">Đơn giá mặc định (đ) <span class="text-red-500">*</span></label>
                        <input type="number" name="donGiaMacDinh" id="donGiaThem" required min="0" step="50000" placeholder="Ví dụ: 2500000"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                    </div>

                    <div>
                        <label for="ghiChuThem" class="block font-medium text-zinc-700 mb-1">Ghi chú đặc điểm phòng</label>
                        <textarea name="ghiChu" id="ghiChuThem" rows="3" placeholder="Tầng 1, có gác lửng, ban công, máy lạnh..."
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
                        Lưu phòng
                    </button>
                </div>
            </form>
        </div>
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

