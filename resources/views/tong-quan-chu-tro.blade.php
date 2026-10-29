<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Nhà Trọ - Trang Chủ Trọ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/tong-quan.css') }}">
    <script src="{{ asset('js/tong-quan.js') }}" defer></script>
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
                            class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-900 text-white">
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
                            class="px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
                            Hợp đồng
                        </a>
                    </nav>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-medium text-zinc-900">{{ Auth::user()->email }}</div>
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

        <!-- NỘI DUNG CHÍNH -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            <!-- BANNER CHÀO MỪNG -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 mb-1">Xin chào, Chủ trọ!</h2>
                    <p class="text-sm text-zinc-600">Dữ liệu thống kê tổng thể phòng trọ, khách thuê và công nợ hiện tại trên hệ thống.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('phong-tro.danh-sach') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                        <span>Quản lý phòng trọ &rarr;</span>
                    </a>
                    <a href="{{ route('khach-thue.danh-sach') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors shadow-xs">
                        <span>Hồ sơ khách thuê &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- CÁC THẺ THỐNG KÊ (DỮ LIỆU TỪ CONTROLLER / DATABASE) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Tổng số phòng -->
                <a href="{{ route('phong-tro.danh-sach') }}" class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs hover:border-zinc-300 transition-all block">
                    <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Tổng số phòng</div>
                    <div class="text-2xl font-bold text-zinc-900 mt-2 tabular-nums">{{ $tongSoPhong }}</div>
                    <div class="text-xs text-zinc-500 mt-1">Đang quản lý</div>
                </a>

                <!-- Phòng đang thuê -->
                <a href="{{ route('phong-tro.danh-sach', ['trangThaiPhong' => 'DANG_THUE']) }}" class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs hover:border-zinc-300 transition-all block">
                    <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Phòng đang thuê</div>
                    <div class="text-2xl font-bold text-sky-700 mt-2 tabular-nums">{{ $phongDangThue }}</div>
                    <div class="text-xs text-zinc-500 mt-1">Đang có khách ở</div>
                </a>

                <!-- Phòng trống -->
                <a href="{{ route('phong-tro.danh-sach', ['trangThaiPhong' => 'TRONG']) }}" class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs hover:border-zinc-300 transition-all block">
                    <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Phòng trống</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-2 tabular-nums">{{ $phongTrong }}</div>
                    <div class="text-xs text-zinc-500 mt-1">Sẵn sàng đón khách</div>
                </a>

                <!-- Hồ sơ khách thuê -->
                <a href="{{ route('khach-thue.danh-sach') }}" class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs hover:border-zinc-300 transition-all block">
                    <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Khách thuê</div>
                    <div class="text-2xl font-bold text-zinc-900 mt-2 tabular-nums">{{ $tongKhachThue }}</div>
                    <div class="text-xs text-zinc-500 mt-1">Đang kích hoạt</div>
                </a>

                <!-- Công nợ chưa thu -->
                <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs block">
                    <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Công nợ chưa thu</div>
                    <div class="text-2xl font-bold text-red-600 mt-2 tabular-nums">
                        {{ number_format($tongTienNo, 0, ',', '.') }} đ
                    </div>
                    <div class="text-xs text-red-600 mt-1 font-medium">
                        {{ $soHoaDonNo }} hóa đơn chưa trả
                    </div>
                </div>
            </div>

            <!-- BẢNG PHÒNG TRỌ VÀ TRẠNG THÁI GẦN NHẤT -->
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 uppercase tracking-wider">Theo dõi phòng trọ</h3>
                        <p class="text-xs text-zinc-500 mt-0.5">Danh sách các phòng trọ cùng trạng thái và khách thuê đại diện.</p>
                    </div>
                    <a href="{{ route('phong-tro.danh-sach') }}" class="text-xs font-medium text-zinc-600 hover:text-zinc-900 transition-colors">
                        Xem tất cả phòng &rarr;
                    </a>
                </div>

                @if ($danhSachPhongGanDay->count() > 0)
                    <div class="overflow-x-auto rounded-lg border border-zinc-200">
                        <table class="min-w-full divide-y divide-zinc-200 text-xs">
                            <thead class="bg-zinc-50 text-zinc-600 font-medium text-left">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Số phòng</th>
                                    <th scope="col" class="px-4 py-3 text-right">Đơn giá niêm yết</th>
                                    <th scope="col" class="px-4 py-3 text-center">Trạng thái</th>
                                    <th scope="col" class="px-4 py-3">Khách đang thuê</th>
                                    <th scope="col" class="px-4 py-3 text-right">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 bg-white">
                                @foreach ($danhSachPhongGanDay as $phong)
                                    <tr class="hover:bg-zinc-50 transition-colors">
                                        <td class="px-4 py-3 font-bold text-zinc-900">
                                            Phòng {{ $phong->soPhong }}
                                        </td>
                                        <td class="px-4 py-3 text-right tabular-nums text-zinc-800">
                                            {{ number_format($phong->donGiaMacDinh, 0, ',', '.') }} đ
                                        </td>
                                        <td class="px-4 py-3 text-center">
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
                                        <td class="px-4 py-3 text-zinc-700">
                                            @if ($phong->hopDongHieuLuc && $phong->hopDongHieuLuc->khachThue)
                                                <span class="font-medium text-zinc-900">{{ $phong->hopDongHieuLuc->khachThue->hoTen }}</span>
                                                <span class="text-zinc-500 text-[11px] block">{{ $phong->hopDongHieuLuc->khachThue->soDienThoai }}</span>
                                            @else
                                                <span class="text-zinc-400 italic">Chưa có khách</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('phong-tro.chi-tiet', $phong->maPhong) }}"
                                                class="px-2.5 py-1 rounded border border-zinc-300 text-zinc-700 text-[11px] font-medium hover:bg-zinc-100 transition-colors">
                                                Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-xs text-zinc-500 bg-zinc-50 rounded-lg border border-zinc-200">
                        Chưa có dữ liệu phòng trọ nào.
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
