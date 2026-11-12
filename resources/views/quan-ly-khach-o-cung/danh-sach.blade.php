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
            <!-- TIÊU ĐỀ & NÚT THAO TÁC -->
            <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-zinc-900">Quản Lý Khách Ở Cùng</h2>
                        <p class="text-xs text-zinc-500 mt-1">
                            Nhập trực tiếp và cập nhật linh hoạt thông tin bạn ở cùng tại các phòng đang có hợp đồng thuê (không bắt buộc tạo hồ sơ bên Khách thuê).
                        </p>
                    </div>

                    <button type="button" id="nutMoModalThemOCung"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs shrink-0">
                        <span>+ Thêm khách ở cùng</span>
                    </button>
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
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
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

            <!-- DANH SÁCH DẠNG BẢNG HÀNG NGANG: PHÒNG / HỢP ĐỒNG & KHÁCH Ở CÙNG -->
            @if ($danhSachHopDong->isEmpty())
                <div class="bg-white border border-zinc-200 rounded-xl p-12 text-center shadow-xs space-y-2">
                    <div class="text-sm font-semibold text-zinc-900">Chưa có hợp đồng đang hiệu lực phù hợp</div>
                    <p class="text-xs text-zinc-500">Hãy ký hợp đồng tại mục <strong>Hợp đồng</strong> trước khi thêm người ở cùng.</p>
                </div>
            @else
                <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden shadow-xs">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 text-xs">
                            <thead class="bg-zinc-50 text-zinc-500 font-medium text-[11px] border-b border-zinc-200">
                                <tr>
                                    <th scope="col" class="px-3 py-2.5 text-left whitespace-nowrap">Phòng & Hợp đồng</th>
                                    <th scope="col" class="px-3 py-2.5 text-left whitespace-nowrap">Đại diện ký HĐ</th>
                                    <th scope="col" class="px-3 py-2.5 text-center whitespace-nowrap">Sức chứa</th>
                                    <th scope="col" class="px-3 py-2.5 text-left whitespace-nowrap">Danh sách khách đang ở cùng</th>
                                    <th scope="col" class="px-3.5 py-2.5 text-right whitespace-nowrap sticky right-0 bg-zinc-50 z-10 shadow-[-6px_0_10px_-4px_rgba(0,0,0,0.06)]">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 bg-white">
                                @foreach ($danhSachHopDong as $hd)
                                    @php
                                        $soNguoiHienTai = 1 + $hd->danhSachNguoiOCung->count();
                                        $soNguoiToiDa = optional($hd->phongTro)->soNguoiToiDa ?? 1;
                                        $conCho = $soNguoiHienTai < $soNguoiToiDa;
                                    @endphp
                                    <tr class="hover:bg-zinc-50/80 transition-colors group">
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <div class="inline-flex items-baseline gap-1.5">
                                                <a href="{{ route('phong-tro.chi-tiet', $hd->maPhong) }}" class="font-bold text-zinc-900 hover:underline">
                                                    P.{{ optional($hd->phongTro)->soPhong }}
                                                </a>
                                                <a href="{{ route('hop-dong.chi-tiet', $hd->maHopDong) }}" class="text-[11px] text-zinc-500 hover:text-zinc-900 hover:underline">
                                                    (#HD-{{ str_pad($hd->maHopDong, 4, '0', STR_PAD_LEFT) }})
                                                </a>
                                            </div>
                                        </td>

                                        <td class="px-3 py-2 whitespace-nowrap">
                                            @if ($hd->khachThue)
                                                <a href="{{ route('khach-thue.chi-tiet', $hd->maKhach) }}" class="font-medium text-zinc-900 hover:underline">
                                                    {{ $hd->khachThue->hoTen }}
                                                </a>
                                                <span class="text-[11px] text-zinc-400 tabular-nums ml-1">({{ $hd->khachThue->soDienThoai }})</span>
                                            @else
                                                <span class="text-zinc-400 italic">—</span>
                                            @endif
                                        </td>

                                        <td class="px-3 py-2 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold tabular-nums {{ $conCho ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-zinc-100 text-zinc-700 border border-zinc-200' }}">
                                                {{ $soNguoiHienTai }} / {{ $soNguoiToiDa }} người
                                            </span>
                                        </td>

                                        <td class="px-3 py-2 whitespace-nowrap">
                                            @if ($hd->danhSachNguoiOCung->isNotEmpty())
                                                <div class="inline-flex items-center gap-2 flex-nowrap">
                                                    @foreach ($hd->danhSachNguoiOCung as $noc)
                                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-zinc-50 border border-zinc-200">
                                                            <span class="font-semibold text-zinc-900">{{ $noc->tenHienThi }}</span>
                                                            @if ($noc->sdtHienThi)
                                                                <span class="text-[11px] text-zinc-500 tabular-nums">({{ $noc->sdtHienThi }})</span>
                                                            @endif
                                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-zinc-200/70 text-zinc-700">{{ $noc->ghiChu ?: 'Ở cùng' }}</span>

                                                            <button type="button" data-hanh-dong="sua-o-cung"
                                                                data-ma-o-cung="{{ $noc->maNguoiOCung }}"
                                                                data-ho-ten="{{ $noc->tenHienThi }}"
                                                                data-sdt="{{ $noc->sdtHienThi }}"
                                                                data-cccd="{{ $noc->cccdHienThi }}"
                                                                data-quan-he="{{ $noc->ghiChu }}"
                                                                data-ngay-vao="{{ $noc->ngayVaoO ? \Carbon\Carbon::parse($noc->ngayVaoO)->format('Y-m-d') : '' }}"
                                                                class="nut-thao-tac nut-thao-tac-sua">
                                                                <span>✎ Sửa</span>
                                                            </button>

                                                            <form action="{{ route('khach-o-cung.roi-phong', $noc->maNguoiOCung) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="nut-thao-tac nut-thao-tac-xoa">
                                                                    <span>Rời phòng</span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-zinc-400 italic text-[11px]">Chỉ có người đại diện</span>
                                            @endif
                                        </td>

                                        <td class="px-3.5 py-2 whitespace-nowrap text-right sticky right-0 bg-white group-hover:bg-zinc-50 z-10 shadow-[-6px_0_10px_-4px_rgba(0,0,0,0.06)]">
                                            <div class="nhom-thao-tac">
                                                @if ($conCho)
                                                    <button type="button"
                                                        data-them-o-cung-hop-dong="{{ $hd->maHopDong }}"
                                                        class="nut-thao-tac nut-thao-tac-chinh">
                                                        <span>+ Thêm ở cùng</span>
                                                    </button>
                                                @else
                                                    <span class="px-2 py-1 rounded bg-zinc-100 text-zinc-400 text-[11px]">Đủ người</span>
                                                @endif

                                                <a href="{{ route('hop-dong.chi-tiet', $hd->maHopDong) }}"
                                                    class="nut-thao-tac">
                                                    <span>Hợp đồng</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    @include('partials.phan-trang-nhanh', ['paginator' => $danhSachHopDong])
                </div>
            @endif
        </main>
    </div>

    <!-- MODAL THÊM KHÁCH Ở CÙNG (NHẬP THỦ CÔNG) -->
    <div id="modalThemNguoiOCung" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/60 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-2xl max-w-2xl w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-zinc-900">Thêm khách ở cùng vào phòng</h3>
                    <p class="text-[11px] text-zinc-500">Nhập trực tiếp thông tin người ở cùng (có thể thay đổi hoặc cho rời phòng bất kỳ lúc nào)</p>
                </div>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-lg">&times;</button>
            </div>

            <form action="{{ route('khach-o-cung.tao-moi') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label for="maHopDongOCung" class="block font-medium text-zinc-700 mb-1">
                        Chọn phòng / Hợp đồng đang hiệu lực <span class="text-red-500">*</span>
                    </label>
                    <select name="maHopDong" id="maHopDongOCung" required
                        class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                        <option value="">-- Chọn hợp đồng đang thuê --</option>
                        @foreach (($danhSachHopDongChon ?? $danhSachHopDong) as $hd)
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
                    <label for="hoTenOCungThem" class="block font-medium text-zinc-700 mb-1">
                        Họ và tên khách ở cùng <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="hoTen" id="hoTenOCungThem" required placeholder="Ví dụ: Trần Văn C"
                        class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="soDienThoaiOCungThem" class="block font-medium text-zinc-700 mb-1">Số điện thoại</label>
                        <input type="text" name="soDienThoai" id="soDienThoaiOCungThem" placeholder="0901234567"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                    </div>
                    <div>
                        <label for="soCCCDOCungThem" class="block font-medium text-zinc-700 mb-1">Số CCCD</label>
                        <input type="text" name="soCCCD" id="soCCCDOCungThem" maxlength="12" placeholder="079012345678"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                    </div>
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
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors">
                        Lưu khách ở cùng
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SỬA KHÁCH Ở CÙNG -->
    <div id="modalSuaNguoiOCung" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/60 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-2xl max-w-2xl w-full p-6">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-zinc-900">Cập nhật thông tin khách ở cùng</h3>
                    <p class="text-[11px] text-zinc-500">Thay đổi họ tên, SĐT, CCCD hoặc quan hệ của người ở cùng</p>
                </div>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-lg">&times;</button>
            </div>

            <form id="bieuMauSuaOCung" action="" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label for="hoTenOCungSua" class="block font-medium text-zinc-700 mb-1">
                        Họ và tên khách ở cùng <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="hoTen" id="hoTenOCungSua" required
                        class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="soDienThoaiOCungSua" class="block font-medium text-zinc-700 mb-1">Số điện thoại</label>
                        <input type="text" name="soDienThoai" id="soDienThoaiOCungSua"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                    </div>
                    <div>
                        <label for="soCCCDOCungSua" class="block font-medium text-zinc-700 mb-1">Số CCCD</label>
                        <input type="text" name="soCCCD" id="soCCCDOCungSua" maxlength="12"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white tabular-nums">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="quanHeOCungSua" class="block font-medium text-zinc-700 mb-1">Quan hệ với người đại diện</label>
                        <input type="text" name="ghiChu" id="quanHeOCungSua"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>
                    <div>
                        <label for="ngayVaoOOCungSua" class="block font-medium text-zinc-700 mb-1">Ngày vào ở</label>
                        <input type="date" name="ngayVaoO" id="ngayVaoOOCungSua"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>
                </div>

                <div class="pt-4 border-t border-zinc-100 flex items-center justify-end gap-2">
                    <button type="button" data-dong-modal
                        class="px-4 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-700 text-xs font-medium hover:bg-zinc-50 transition-colors">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
