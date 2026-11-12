<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khách Ở Cùng - Hệ Thống Quản Lý Nhà Trọ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/quan-ly-khach-o-cung.css') }}">
    <script src="{{ asset('js/quan-ly-khach-o-cung.js') }}" defer></script>
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
                            class="px-3 py-1.5 rounded-lg text-xs font-medium text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
                            Hợp đồng
                        </a>
                        <a href="{{ route('khach-o-cung.danh-sach') }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-900 text-white">
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
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- MAIN -->
        <main class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            <!-- TIÊU ĐỀ & THANH QUY TRÌNH 4 BƯỚC -->
            <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-zinc-900">Quản Lý Khách Ở Cùng (Bước 4)</h2>
                        <p class="text-xs text-zinc-500 mt-1">
                            Sau khi đã tạo phòng, nhập hồ sơ khách thuê và ký hợp đồng đại diện, bạn thực hiện ghép thêm bạn ở cùng vào các hợp đồng đang hiệu lực tại đây.
                        </p>
                    </div>

                    <button type="button" id="nutMoModalThemOCung"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs shrink-0">
                        <span>+ Thêm khách ở cùng</span>
                    </button>
                </div>

                <!-- Sơ đồ 4 bước nghiệp vụ -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 pt-3 border-t border-zinc-100 text-xs">
                    <a href="{{ route('phong-tro.danh-sach') }}" class="p-2.5 rounded-lg border border-zinc-200 bg-zinc-50 hover:bg-zinc-100 transition-colors flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-zinc-200 text-zinc-800 font-bold flex items-center justify-center text-[11px]">1</span>
                        <div>
                            <div class="font-semibold text-zinc-900">Bước 1: Quản lý phòng</div>
                            <div class="text-[11px] text-zinc-500">Khởi tạo phòng & sức chứa</div>
                        </div>
                    </a>
                    <a href="{{ route('khach-thue.danh-sach') }}" class="p-2.5 rounded-lg border border-zinc-200 bg-zinc-50 hover:bg-zinc-100 transition-colors flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-zinc-200 text-zinc-800 font-bold flex items-center justify-center text-[11px]">2</span>
                        <div>
                            <div class="font-semibold text-zinc-900">Bước 2: Khách thuê</div>
                            <div class="text-[11px] text-zinc-500">Nhập hồ sơ nhân thân trước</div>
                        </div>
                    </a>
                    <a href="{{ route('hop-dong.danh-sach') }}" class="p-2.5 rounded-lg border border-zinc-200 bg-zinc-50 hover:bg-zinc-100 transition-colors flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-zinc-200 text-zinc-800 font-bold flex items-center justify-center text-[11px]">3</span>
                        <div>
                            <div class="font-semibold text-zinc-900">Bước 3: Hợp đồng</div>
                            <div class="text-[11px] text-zinc-500">Ký hợp đồng người đại diện</div>
                        </div>
                    </a>
                    <div class="p-2.5 rounded-lg border border-zinc-900 bg-zinc-900 text-white flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-white text-zinc-900 font-bold flex items-center justify-center text-[11px]">4</span>
                        <div>
                            <div class="font-semibold">Bước 4: Khách ở cùng</div>
                            <div class="text-[11px] text-zinc-300">Ghép bạn cùng phòng vào HĐ</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- THÔNG BÁO -->
            @if (session('thongBaoThanhCong'))
                <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/70 text-emerald-800 text-xs flex items-center justify-between">
                    <span>{{ session('thongBaoThanhCong') }}</span>
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

            <!-- THẺ KPI -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <div class="text-xs text-zinc-500">Hợp đồng đang hiệu lực</div>
                    <div class="text-2xl font-bold text-zinc-900 mt-1 tabular-nums">{{ $soHopDongHieuLuc }}</div>
                    <div class="text-[11px] text-zinc-400 mt-1">Phòng đang có người đại diện thuê</div>
                </div>
                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <div class="text-xs text-zinc-500">Tổng số khách đang ở cùng</div>
                    <div class="text-2xl font-bold text-sky-700 mt-1 tabular-nums">{{ $tongNguoiOCung }}</div>
                    <div class="text-[11px] text-zinc-400 mt-1">Bạn cùng phòng đang cư trú</div>
                </div>
                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <div class="text-xs text-zinc-500">Phòng đang thuê còn chỗ ghép</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-1 tabular-nums">{{ $soPhongConCho }}</div>
                    <div class="text-[11px] text-zinc-400 mt-1">Chưa chạm mức sức chứa tối đa</div>
                </div>
                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <div class="text-xs text-zinc-500">Hồ sơ khách chờ xếp phòng</div>
                    <div class="text-2xl font-bold text-zinc-900 mt-1 tabular-nums">{{ $danhSachKhachCoTheThem->count() }}</div>
                    <div class="text-[11px] text-zinc-400 mt-1">Đã nhập ở Bước 2, sẵn sàng ghép</div>
                </div>
            </div>

            <!-- BỘ LỌC TÌM KIẾM -->
            <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                <form action="{{ route('khach-o-cung.danh-sach') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="text" name="tuKhoa" value="{{ request('tuKhoa') }}"
                        placeholder="Tìm theo số phòng, tên người đại diện, hoặc tên/CCCD/SĐT người ở cùng..."
                        class="flex-1 min-w-[260px] px-3.5 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors">
                        Tìm kiếm
                    </button>
                    @if (request('tuKhoa'))
                        <a href="{{ route('khach-o-cung.danh-sach') }}"
                            class="px-3 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-600 text-xs font-medium hover:bg-zinc-100 transition-colors">
                            Xóa lọc
                        </a>
                    @endif
                </form>
            </div>

            <!-- DANH SÁCH CÁC HỢP ĐỒNG ĐANG THUÊ & NGƯỜI Ở CÙNG -->
            @if ($danhSachHopDong->isEmpty())
                <div class="bg-white border border-zinc-200 rounded-xl p-12 text-center shadow-xs space-y-2">
                    <div class="text-sm font-semibold text-zinc-900">Chưa có hợp đồng đang hiệu lực phù hợp</div>
                    <p class="text-xs text-zinc-500">Hãy ký hợp đồng tại mục <strong>Hợp đồng (Bước 3)</strong> trước khi thêm người ở cùng.</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    @foreach ($danhSachHopDong as $hd)
                        @php
                            $soNguoiHienTai = 1 + $hd->danhSachNguoiOCung->count();
                            $soNguoiToiDa = optional($hd->phongTro)->soNguoiToiDa ?? 1;
                            $conCho = $soNguoiHienTai < $soNguoiToiDa;
                        @endphp
                        <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                <!-- Header thẻ hợp đồng -->
                                <div class="flex items-start justify-between gap-3 pb-3 border-b border-zinc-100">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('phong-tro.chi-tiet', $hd->maPhong) }}" class="text-base font-bold text-zinc-900 hover:underline">
                                                Phòng {{ optional($hd->phongTro)->soPhong }}
                                            </a>
                                            <span class="text-xs text-zinc-400">&bull;</span>
                                            <a href="{{ route('hop-dong.chi-tiet', $hd->maHopDong) }}" class="text-xs font-medium text-zinc-600 hover:text-zinc-900 hover:underline">
                                                #HD-{{ str_pad($hd->maHopDong, 4, '0', STR_PAD_LEFT) }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-zinc-500 mt-0.5">
                                            {{ optional(optional($hd->phongTro)->loaiPhong)->tenLoaiPhong ?? 'Phòng trọ' }} &bull;
                                            Đại diện ký HĐ:
                                            @if ($hd->khachThue)
                                                <a href="{{ route('khach-thue.chi-tiet', $hd->maKhach) }}" class="font-semibold text-zinc-900 hover:underline">
                                                    {{ $hd->khachThue->hoTen }}
                                                </a>
                                                ({{ $hd->khachThue->soDienThoai }})
                                            @endif
                                        </div>
                                    </div>

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold tabular-nums {{ $conCho ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-zinc-100 text-zinc-700 border border-zinc-200' }}">
                                        {{ $soNguoiHienTai }} / {{ $soNguoiToiDa }} người
                                    </span>
                                </div>

                                <!-- Danh sách bạn cùng phòng -->
                                @if ($hd->danhSachNguoiOCung->isNotEmpty())
                                    <div class="space-y-2">
                                        <div class="text-[11px] font-semibold text-zinc-500 uppercase tracking-wider">
                                            Danh sách người ở cùng ({{ $hd->danhSachNguoiOCung->count() }})
                                        </div>
                                        <div class="divide-y divide-zinc-100 border border-zinc-200 rounded-lg overflow-hidden">
                                            @foreach ($hd->danhSachNguoiOCung as $noc)
                                                <div class="p-3 bg-white flex items-center justify-between gap-3 text-xs">
                                                    <div>
                                                        <div class="font-semibold text-zinc-900">
                                                            @if ($noc->khachThue)
                                                                <a href="{{ route('khach-thue.chi-tiet', $noc->maKhach) }}" class="hover:underline">
                                                                    {{ $noc->khachThue->hoTen }}
                                                                </a>
                                                            @endif
                                                            <span class="ml-1.5 px-2 py-0.5 rounded bg-zinc-100 text-zinc-600 text-[11px] font-normal">
                                                                {{ $noc->ghiChu ?: 'Ở cùng' }}
                                                            </span>
                                                        </div>
                                                        <div class="text-[11px] text-zinc-500 mt-0.5 tabular-nums">
                                                            SĐT: {{ optional($noc->khachThue)->soDienThoai }} &bull;
                                                            CCCD: {{ optional($noc->khachThue)->soCCCD }} &bull;
                                                            Vào ở: {{ $noc->ngayVaoO ? \Carbon\Carbon::parse($noc->ngayVaoO)->format('d/m/Y') : '—' }}
                                                        </div>
                                                    </div>

                                                    <form action="{{ route('khach-o-cung.roi-phong', $noc->maNguoiOCung) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="px-2.5 py-1 rounded border border-red-200 bg-red-50 text-red-700 hover:bg-red-100 text-[11px] font-medium transition-colors">
                                                            Rời phòng
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="p-3 rounded-lg bg-zinc-50 border border-zinc-200 text-xs text-zinc-500">
                                        Chưa có người ở cùng (hiện chỉ có người đại diện ở).
                                    </div>
                                @endif
                            </div>

                            <!-- Footer thao tác của thẻ -->
                            <div class="pt-3 border-t border-zinc-100 flex items-center justify-between text-xs">
                                <a href="{{ route('hop-dong.chi-tiet', $hd->maHopDong) }}" class="text-zinc-600 hover:text-zinc-900 hover:underline">
                                    Xem hợp đồng &rarr;
                                </a>

                                @if ($conCho)
                                    <button type="button"
                                        data-them-o-cung-hop-dong="{{ $hd->maHopDong }}"
                                        class="px-3 py-1.5 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors">
                                        + Thêm người ở cùng vào P.{{ optional($hd->phongTro)->soPhong }}
                                    </button>
                                @else
                                    <span class="text-[11px] text-zinc-400 italic">Phòng đã đủ sức chứa tối đa</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>

    <!-- MODAL THÊM KHÁCH Ở CÙNG -->
    <div id="modalThemNguoiOCung" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/60 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-2xl max-w-lg w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-zinc-900">Thêm khách ở cùng vào hợp đồng</h3>
                    <p class="text-[11px] text-zinc-500">Chọn hợp đồng còn chỗ và khách thuê đã nhập hồ sơ ở Bước 2</p>
                </div>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-lg">&times;</button>
            </div>

            <form action="{{ route('khach-o-cung.tao-moi') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label for="maHopDongOCung" class="block font-medium text-zinc-700 mb-1">
                        1. Chọn phòng / Hợp đồng đang hiệu lực <span class="text-red-500">*</span>
                    </label>
                    <select name="maHopDong" id="maHopDongOCung" required
                        class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                        <option value="">-- Chọn hợp đồng đang thuê --</option>
                        @foreach ($danhSachHopDong as $hd)
                            @php
                                $soNguoiHienTai = 1 + $hd->danhSachNguoiOCung->count();
                                $soNguoiToiDa = optional($hd->phongTro)->soNguoiToiDa ?? 1;
                                $hetCho = $soNguoiHienTai >= $soNguoiToiDa;
                            @endphp
                            <option value="{{ $hd->maHopDong }}" {{ $hetCho ? 'disabled' : '' }}>
                                Phòng {{ optional($hd->phongTro)->soPhong }} (Đại diện: {{ optional($hd->khachThue)->hoTen }}) - Đang ở: {{ $soNguoiHienTai }}/{{ $soNguoiToiDa }} người {{ $hetCho ? '[ĐÃ ĐỦ NGƯỜI]' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="maKhachOCung" class="block font-medium text-zinc-700 mb-1">
                        2. Chọn hồ sơ khách ở cùng <span class="text-red-500">*</span>
                    </label>
                    @if ($danhSachKhachCoTheThem->isNotEmpty())
                        <select name="maKhach" id="maKhachOCung" required
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                            <option value="">-- Chọn khách thuê chưa xếp phòng --</option>
                            @foreach ($danhSachKhachCoTheThem as $k)
                                <option value="{{ $k->maKhach }}">
                                    {{ $k->hoTen }} (SĐT: {{ $k->soDienThoai }} - CCCD: {{ $k->soCCCD }})
                                </option>
                            @endforeach
                        </select>
                    @else
                        <div class="p-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 space-y-1">
                            <div>Hiện chưa có hồ sơ khách tự do nào.</div>
                            <div>
                                Vui lòng sang <a href="{{ route('khach-thue.danh-sach') }}" class="font-semibold underline">Bước 2: Quản lý khách thuê</a> để nhập thông tin khách trước.
                            </div>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="quanHeOCung" class="block font-medium text-zinc-700 mb-1">Quan hệ với người đại diện</label>
                        <input type="text" name="ghiChu" id="quanHeOCung" placeholder="Ví dụ: Bạn cùng phòng, Vợ/Chồng..."
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>
                    <div>
                        <label for="ngayVaoOOCung" class="block font-medium text-zinc-700 mb-1">Ngày vào ở</label>
                        <input type="date" name="ngayVaoO" id="ngayVaoOOCung" value="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>
                </div>

                <div class="pt-4 border-t border-zinc-100 flex items-center justify-end gap-2">
                    <button type="button" data-dong-modal
                        class="px-4 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors">
                        Hủy
                    </button>
                    <button type="submit" {{ $danhSachKhachCoTheThem->isEmpty() ? 'disabled' : '' }}
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors disabled:opacity-50">
                        Lưu khách ở cùng
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

