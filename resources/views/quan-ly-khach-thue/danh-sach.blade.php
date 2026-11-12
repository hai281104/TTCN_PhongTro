<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khách Thuê - Hệ Thống Quản Lý Nhà Trọ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/quan-ly-khach.css') }}">
    <script src="{{ asset('js/quan-ly-khach.js') }}" defer></script>
</head>
<body class="h-full font-sans antialiased text-zinc-900 bg-zinc-50">
    <div class="min-h-full">
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

        <main class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

            <!-- KPI THỐNG KÊ HỒ SƠ -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <div class="text-[11px] font-medium text-zinc-500 uppercase tracking-wider">Tổng số khách</div>
                    <div class="text-2xl font-bold text-zinc-900 mt-1 tabular-nums">{{ $tongSoKhach }}</div>
                </div>

                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <div class="text-[11px] font-medium text-zinc-500 uppercase tracking-wider">Đang thuê phòng</div>
                    <div class="text-2xl font-bold text-sky-700 mt-1 tabular-nums">{{ $soKhachDangThue }}</div>
                </div>

                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <div class="text-[11px] font-medium text-zinc-500 uppercase tracking-wider">Chưa thuê phòng</div>
                    <div class="text-2xl font-bold text-zinc-700 mt-1 tabular-nums">{{ $soKhachChuaThue }}</div>
                </div>

                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs">
                    <div class="text-[11px] font-medium text-zinc-500 uppercase tracking-wider">Chưa làm tạm trú</div>
                    <div class="text-2xl font-bold text-red-600 mt-1 tabular-nums">{{ $soKhachChuaTamTru }}</div>
                </div>

                <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs col-span-2 md:col-span-1">
                    <div class="text-[11px] font-medium text-zinc-500 uppercase tracking-wider">Đã có tài khoản</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-1 tabular-nums">{{ $soKhachCoTaiKhoan }}</div>
                </div>
            </div>

            <!-- THANH TÌM KIẾM & BỘ LỌC -->
            <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <form action="{{ route('khach-thue.danh-sach') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
                        <div class="relative w-full sm:w-64">
                            <input type="text" name="tuKhoa" value="{{ request('tuKhoa') }}"
                                placeholder="Tìm theo tên, CCCD, SĐT, biển xe..."
                                class="w-full pl-9 pr-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-all">
                            <svg class="w-4 h-4 text-zinc-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <select name="tinhTrang"
                            class="px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-all">
                            <option value="">Tất cả tình trạng</option>
                            <option value="DANG_THUE" {{ request('tinhTrang') === 'DANG_THUE' ? 'selected' : '' }}>Đang ở (Tất cả)</option>
                            <option value="DAI_DIEN" {{ request('tinhTrang') === 'DAI_DIEN' ? 'selected' : '' }}>Đại diện hợp đồng</option>
                            <option value="O_CUNG" {{ request('tinhTrang') === 'O_CUNG' ? 'selected' : '' }}>Người ở cùng phòng</option>
                            <option value="CHUA_THUE" {{ request('tinhTrang') === 'CHUA_THUE' ? 'selected' : '' }}>Chưa thuê / Đã trả</option>
                        </select>

                        <select name="maPhong"
                            class="px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-all">
                            <option value="">Tất cả phòng</option>
                            <option value="CHUA_CO" {{ request('maPhong') === 'CHUA_CO' ? 'selected' : '' }}>Chưa gán phòng</option>
                            @foreach ($danhSachPhongTro as $p)
                                <option value="{{ $p->maPhong }}" {{ request('maPhong') == $p->maPhong ? 'selected' : '' }}>
                                    Phòng {{ $p->soPhong }}
                                </option>
                            @endforeach
                        </select>

                        <select name="tamTru"
                            class="px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-all">
                            <option value="">Tình trạng tạm trú</option>
                            <option value="DA_DANG_KY" {{ request('tamTru') === 'DA_DANG_KY' ? 'selected' : '' }}>Đã đăng ký tạm trú</option>
                            <option value="CHUA_DANG_KY" {{ request('tamTru') === 'CHUA_DANG_KY' ? 'selected' : '' }}>Chưa đăng ký tạm trú</option>
                        </select>

                        <select name="taiKhoan"
                            class="px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-xs text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white transition-all">
                            <option value="">Tài khoản đăng nhập</option>
                            <option value="CO_TAI_KHOAN" {{ request('taiKhoan') === 'CO_TAI_KHOAN' ? 'selected' : '' }}>Đã có tài khoản</option>
                            <option value="CHUA_CO_TAI_KHOAN" {{ request('taiKhoan') === 'CHUA_CO_TAI_KHOAN' ? 'selected' : '' }}>Chưa có tài khoản</option>
                        </select>

                        <button type="submit"
                            class="px-3.5 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                            Lọc
                        </button>

                        @if (request()->hasAny(['tuKhoa', 'tinhTrang', 'maPhong', 'tamTru', 'taiKhoan']))
                            <a href="{{ route('khach-thue.danh-sach') }}"
                                class="px-3 py-2 rounded-lg border border-zinc-300 bg-white text-zinc-600 text-xs font-medium hover:bg-zinc-100 transition-colors">
                                Xóa bộ lọc
                            </a>
                        @endif
                    </form>

                    <button type="button" id="nutMoThemKhach"
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-zinc-900 text-white text-xs font-medium hover:bg-zinc-800 transition-colors shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Thêm hồ sơ khách</span>
                    </button>
                </div>
            </div>

            @if ($danhSachKhach->isEmpty())
                <div class="bg-white border border-zinc-200 rounded-xl p-12 text-center shadow-xs">
                    <svg class="w-12 h-12 text-zinc-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-sm font-semibold text-zinc-900 mb-1">Không tìm thấy hồ sơ khách thuê nào</h3>
                    <p class="text-xs text-zinc-500 mb-4">Thử thay đổi bộ lọc tìm kiếm hoặc thêm hồ sơ khách thuê mới.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
                    @foreach ($danhSachKhach as $khach)
                        <div class="bg-white border border-zinc-200 rounded-xl p-4 shadow-xs flex flex-col justify-between hover:border-zinc-300 transition-all">
                            <div>
                                <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-zinc-900 text-white flex items-center justify-center font-bold text-xs">
                                            {{ mb_substr($khach->hoTen, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-zinc-900 leading-tight flex items-center gap-1.5">
                                                <span>{{ $khach->hoTen }}</span>
                                                <span class="text-[10px] text-zinc-500 font-normal">
                                                    ({{ $khach->gioiTinh === 'NU' ? 'Nữ' : ($khach->gioiTinh === 'KHAC' ? 'Khác' : 'Nam') }}{{ $khach->ngaySinh ? ', ' . \Carbon\Carbon::parse($khach->ngaySinh)->format('Y') : '' }})
                                                </span>
                                            </div>
                                            <div class="text-[11px] text-zinc-500 tabular-nums">{{ $khach->soDienThoai }}</div>
                                        </div>
                                    </div>

                                    @php
                                        $thongTinPhong = $khach->layThongTinPhongHienTai();
                                    @endphp
                                    @if ($thongTinPhong && $thongTinPhong['phongTro'])
                                        <div class="flex items-center gap-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-sky-50 text-sky-800 border border-sky-200" title="Phòng {{ $thongTinPhong['phongTro']->soPhong }}">
                                                {{ $thongTinPhong['phongTro']->soPhong }}
                                            </span>
                                            @if ($thongTinPhong['laDaiDien'])
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-200" title="Đại diện hợp đồng">
                                                    Đại diện
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-200" title="Người ở cùng phòng">
                                                    Ở cùng
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-zinc-100 text-zinc-600 border border-zinc-200">
                                            Chưa thuê
                                        </span>
                                    @endif
                                </div>

                                <div class="space-y-1.5 mb-3 text-xs">
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-zinc-500 text-[11px]">CCCD:</span>
                                        <span class="font-medium text-zinc-900 tabular-nums text-[11px]">{{ $khach->soCCCD }}</span>
                                    </div>

                                    <div class="flex items-baseline justify-between">
                                        <span class="text-zinc-500 text-[11px]">Biển số xe:</span>
                                        <span class="font-medium text-zinc-800 text-[11px]">{{ $khach->bienSoXe ?: 'Chưa có' }}</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-zinc-500 text-[11px]">Tạm trú:</span>
                                        <form action="{{ route('khach-thue.doi-trang-thai-tam-tru', $khach->maKhach) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="Nhấp để đổi trạng thái tạm trú"
                                                class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-medium transition-colors {{ $khach->daDangKyTamTru ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-red-50 text-red-700 border border-red-200 hover:bg-red-100' }}">
                                                <span>{{ $khach->daDangKyTamTru ? '✓ Đã ĐK' : '! Chưa ĐK' }}</span>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="flex items-baseline justify-between pt-1">
                                        <span class="text-zinc-500 text-[11px]">Tài khoản:</span>
                                        @if ($khach->nguoiDung)
                                            <span class="font-medium text-emerald-700 truncate max-w-[120px] text-right text-[11px]" title="{{ $khach->nguoiDung->email }}">
                                                {{ $khach->nguoiDung->email }}
                                            </span>
                                        @else
                                            <span class="text-amber-700 text-[10px]">Chưa cấp</span>
                                        @endif
                                    </div>

                                    <div class="pt-1 text-[11px] text-zinc-500 truncate" title="{{ $khach->diaChi ?: 'Chưa cập nhật quê quán' }}">
                                        📍 {{ $khach->diaChi ?: 'Chưa cập nhật quê quán' }}
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-zinc-100 flex items-center justify-between gap-1">
                                <button type="button" data-hanh-dong="xem-chi-tiet" data-ma-khach="{{ $khach->maKhach }}"
                                    class="px-2 py-1 rounded bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-[11px] font-medium transition-colors">
                                    Chi tiết
                                </button>

                                <button type="button" data-hanh-dong="sua-khach"
                                    data-ma-khach="{{ $khach->maKhach }}"
                                    data-ho-ten="{{ $khach->hoTen }}"
                                    data-ngay-sinh="{{ $khach->ngaySinh ? \Carbon\Carbon::parse($khach->ngaySinh)->format('Y-m-d') : '' }}"
                                    data-gioi-tinh="{{ $khach->gioiTinh }}"
                                    data-so-cccd="{{ $khach->soCCCD }}"
                                    data-ngay-cap-cccd="{{ $khach->ngayCapCCCD ? \Carbon\Carbon::parse($khach->ngayCapCCCD)->format('Y-m-d') : '' }}"
                                    data-noi-cap-cccd="{{ $khach->noiCapCCCD }}"
                                    data-so-dien-thoai="{{ $khach->soDienThoai }}"
                                    data-dia-chi="{{ $khach->diaChi }}"
                                    data-nghe-nghiep="{{ $khach->ngheNghiep }}"
                                    data-bien-so-xe="{{ $khach->bienSoXe }}"
                                    data-nguoi-khan-cap="{{ $khach->nguoiLienHeKhanCap }}"
                                    data-sdt-khan-cap="{{ $khach->sdtKhanCap }}"
                                    data-quan-he-khan-cap="{{ $khach->quanHeKhanCap }}"
                                    data-tam-tru="{{ $khach->daDangKyTamTru ? '1' : '0' }}"
                                    data-la-dai-dien="{{ $thongTinPhong && $thongTinPhong['laDaiDien'] ? '1' : '0' }}"
                                    data-ma-hop-dong-o-cung="{{ $thongTinPhong && !$thongTinPhong['laDaiDien'] && $thongTinPhong['hopDong'] ? $thongTinPhong['hopDong']->maHopDong : '' }}"
                                    data-quan-he-o-cung="{{ $thongTinPhong && !$thongTinPhong['laDaiDien'] ? ($thongTinPhong['quanHe'] ?? '') : '' }}"
                                    data-ten-phong="{{ $thongTinPhong && $thongTinPhong['phongTro'] ? $thongTinPhong['phongTro']->soPhong : '' }}"
                                    class="px-2 py-1 rounded border border-zinc-200 hover:bg-zinc-50 text-zinc-700 text-[11px] font-medium transition-colors">
                                    Sửa
                                </button>

                                @if (!$khach->maNguoiDung)
                                    <button type="button" data-hanh-dong="cap-tai-khoan"
                                        data-ma-khach="{{ $khach->maKhach }}"
                                        data-ho-ten="{{ $khach->hoTen }}"
                                        class="px-2 py-1 rounded border border-emerald-200 text-emerald-700 hover:bg-emerald-50 text-[11px] font-medium transition-colors"
                                        title="Cấp tài khoản đăng nhập cho khách này">
                                        Cấp TK
                                    </button>
                                @endif

                                @if (!$khach->hopDongHieuLuc)
                                    <button type="button" data-hanh-dong="xoa-khach"
                                        data-ma-khach="{{ $khach->maKhach }}"
                                        data-ho-ten="{{ $khach->hoTen }}"
                                        class="px-2 py-1 rounded border border-red-200 text-red-600 hover:bg-red-50 text-[11px] font-medium transition-colors">
                                        Xóa
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $danhSachKhach->links() }}
                </div>
            @endif
        </main>
    </div>

    <!-- MODAL THÊM KHÁCH THUÊ (TOÀN MÀN HÌNH RỘNG RÃI) -->
    <div id="modalThemKhach" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/60 backdrop-blur-xs items-center justify-center p-3 sm:p-5 md:p-8">
        <div class="chuyen-dong-modal bg-white rounded-2xl border border-zinc-200 shadow-2xl max-w-5xl xl:max-w-6xl w-full max-h-[94vh] flex flex-col overflow-hidden">
            <!-- Header Modal -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 bg-zinc-50/70 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                        +
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900">Thêm hồ sơ khách thuê mới</h3>
                        <p class="text-[11px] text-zinc-500">Thông tin nhân thân, giấy tờ tùy thân và liên hệ khẩn cấp</p>
                    </div>
                </div>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 p-1.5 rounded-lg hover:bg-zinc-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form Body (Chia 2 Cột Song Song) -->
            <form action="{{ route('khach-thue.tao-moi') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <div class="p-6 overflow-y-auto flex-1 text-xs">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- CỘT TRÁI: THÔNG TIN CÁ NHÂN & TÀI KHOẢN -->
                        <div class="space-y-4">
                            <!-- KHỐI 1: THÔNG TIN NHÂN THÂN -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3.5">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                    <span>1. Thông tin cá nhân</span>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="hoTenThem" class="block font-medium text-zinc-700 mb-1">Họ và tên <span class="text-red-500">*</span></label>
                                        <input type="text" name="hoTen" id="hoTenThem" required placeholder="Ví dụ: Nguyễn Văn B"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>

                                    <div>
                                        <label for="gioiTinhThem" class="block font-medium text-zinc-700 mb-1">Giới tính <span class="text-red-500">*</span></label>
                                        <select name="gioiTinh" id="gioiTinhThem" required
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                            <option value="NAM">Nam</option>
                                            <option value="NU">Nữ</option>
                                            <option value="KHAC">Khác</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="ngaySinhThem" class="block font-medium text-zinc-700 mb-1">Ngày sinh</label>
                                        <input type="date" name="ngaySinh" id="ngaySinhThem"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>

                                    <div>
                                        <label for="ngheNghiepThem" class="block font-medium text-zinc-700 mb-1">Nghề nghiệp / Đơn vị</label>
                                        <input type="text" name="ngheNghiep" id="ngheNghiepThem" placeholder="Ví dụ: Kỹ sư, Sinh viên..."
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="soDienThoaiThem" class="block font-medium text-zinc-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                                        <input type="text" name="soDienThoai" id="soDienThoaiThem" required placeholder="0901234567"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                                    </div>

                                    <div>
                                        <label for="diaChiThem" class="block font-medium text-zinc-700 mb-1">Địa chỉ thường trú / Quê quán</label>
                                        <input type="text" name="diaChi" id="diaChiThem" placeholder="Ví dụ: Quận 7, TP. Hồ Chí Minh"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>
                                </div>
                            </div>

                            <!-- KHỐI 4: TÀI KHOẢN ĐĂNG NHẬP -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                        <span>4. Tài khoản đăng nhập hệ thống</span>
                                    </span>
                                </div>

                                <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg bg-white border border-zinc-200 hover:border-zinc-300 transition-colors">
                                    <input type="checkbox" name="taoTaiKhoan" id="taoTaiKhoanThem" value="1"
                                        class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                                    <span class="font-medium text-zinc-800">Cấp tài khoản đăng nhập Cổng khách thuê ngay</span>
                                </label>

                                <div id="vungThongTinTaiKhoanThem" class="hidden space-y-3 pt-2">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="emailThem" class="block font-medium text-zinc-700 mb-1">Email đăng nhập <span class="text-red-500">*</span></label>
                                            <input type="email" name="email" id="emailThem" placeholder="khach@example.com"
                                                class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                        </div>
                                        <div>
                                            <label for="matKhauThem" class="block font-medium text-zinc-700 mb-1">Mật khẩu khởi tạo <span class="text-red-500">*</span></label>
                                            <input type="password" name="matKhau" id="matKhauThem" placeholder="Tối thiểu 6 ký tự"
                                                class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CỘT PHẢI: GIẤY TỜ CCCD, TẠM TRÚ & LIÊN HỆ KHẨN CẤP -->
                        <div class="space-y-4">
                            <!-- KHỐI 2: GIẤY TỜ & CƯ TRÚ -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3.5">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                        <span>2. Giấy tờ CCCD & Tạm trú</span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="soCCCDThem" class="block font-medium text-zinc-700 mb-1">Số CCCD (12 số) <span class="text-red-500">*</span></label>
                                        <input type="text" name="soCCCD" id="soCCCDThem" required maxlength="12" placeholder="079012345678"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                                    </div>

                                    <div>
                                        <label for="ngayCapCCCDThem" class="block font-medium text-zinc-700 mb-1">Ngày cấp thẻ</label>
                                        <input type="date" name="ngayCapCCCD" id="ngayCapCCCDThem"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="noiCapCCCDThem" class="block font-medium text-zinc-700 mb-1">Nơi cấp CCCD</label>
                                        <input type="text" name="noiCapCCCD" id="noiCapCCCDThem" value="Cục CSQLHC về TTXH"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>

                                    <div class="flex items-center pt-5">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="daDangKyTamTru" id="daDangKyTamTruThem" value="1"
                                                class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                                            <span class="font-medium text-zinc-800">Đã đăng ký tạm trú với Công an</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- KHỐI 3: PHƯƠNG TIỆN & LIÊN HỆ KHẨN CẤP -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3.5">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                    <span>3. Phương tiện & Liên hệ khẩn cấp</span>
                                </div>

                                <div>
                                    <label for="bienSoXeThem" class="block font-medium text-zinc-700 mb-1">Biển số xe máy (gửi xe)</label>
                                    <input type="text" name="bienSoXe" id="bienSoXeThem" placeholder="Ví dụ: 59-X1 123.45"
                                        class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                </div>

                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <label for="nguoiLienHeKhanCapThem" class="block font-medium text-zinc-700 mb-1">Người thân khẩn cấp</label>
                                        <input type="text" name="nguoiLienHeKhanCap" id="nguoiLienHeKhanCapThem" placeholder="Họ tên người thân"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>

                                    <div>
                                        <label for="sdtKhanCapThem" class="block font-medium text-zinc-700 mb-1">SĐT người thân</label>
                                        <input type="text" name="sdtKhanCap" id="sdtKhanCapThem" placeholder="0912..."
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                                    </div>

                                    <div>
                                        <label for="quanHeKhanCapThem" class="block font-medium text-zinc-700 mb-1">Mối quan hệ</label>
                                        <input type="text" name="quanHeKhanCap" id="quanHeKhanCapThem" placeholder="Bố/Mẹ/Anh..."
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>
                                </div>
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
                        Lưu hồ sơ khách
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SỬA KHÁCH THUÊ (TOÀN MÀN HÌNH RỘNG RÃI) -->
    <div id="modalSuaKhach" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/60 backdrop-blur-xs items-center justify-center p-3 sm:p-5 md:p-8">
        <div class="chuyen-dong-modal bg-white rounded-2xl border border-zinc-200 shadow-2xl max-w-5xl xl:max-w-6xl w-full max-h-[94vh] flex flex-col overflow-hidden">
            <!-- Header Modal -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 bg-zinc-50/70 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                        ✎
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900">Cập nhật hồ sơ khách thuê</h3>
                        <p class="text-[11px] text-zinc-500">Chỉnh sửa thông tin nhân thân, tạm trú và liên hệ</p>
                    </div>
                </div>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 p-1.5 rounded-lg hover:bg-zinc-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form Body (Chia 2 Cột Song Song) -->
            <form id="bieuMauSuaKhach" action="" method="POST" class="flex flex-col flex-1 overflow-hidden">
                @csrf
                @method('PUT')
                <div class="p-6 overflow-y-auto flex-1 text-xs">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- CỘT TRÁI: THÔNG TIN CÁ NHÂN -->
                        <div class="space-y-4">
                            <!-- KHỐI 1: THÔNG TIN NHÂN THÂN -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3.5">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                    <span>1. Thông tin cá nhân</span>
                                </div>

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
                                        <label for="ngheNghiepSua" class="block font-medium text-zinc-700 mb-1">Nghề nghiệp / Đơn vị</label>
                                        <input type="text" name="ngheNghiep" id="ngheNghiepSua" placeholder="Ví dụ: Kỹ sư, Sinh viên..."
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="soDienThoaiSua" class="block font-medium text-zinc-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                                        <input type="text" name="soDienThoai" id="soDienThoaiSua" required
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                                    </div>

                                    <div>
                                        <label for="diaChiSua" class="block font-medium text-zinc-700 mb-1">Địa chỉ thường trú / Quê quán</label>
                                        <input type="text" name="diaChi" id="diaChiSua"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CỘT PHẢI: GIẤY TỜ CCCD, TẠM TRÚ & LIÊN HỆ KHẨN CẤP -->
                        <div class="space-y-4">
                            <!-- KHỐI 2: GIẤY TỜ & CƯ TRÚ -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3.5">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                        <span>2. Giấy tờ CCCD & Tạm trú</span>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
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
                                            <span class="font-medium text-zinc-800">Đã đăng ký tạm trú với Công an</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- KHỐI 3: PHƯƠNG TIỆN & LIÊN HỆ KHẨN CẤP -->
                            <div class="bg-zinc-50 p-4 rounded-xl border border-zinc-200 space-y-3.5">
                                <div class="text-[11px] font-semibold text-zinc-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-900"></span>
                                    <span>3. Phương tiện & Liên hệ khẩn cấp</span>
                                </div>

                                <div>
                                    <label for="bienSoXeSua" class="block font-medium text-zinc-700 mb-1">Biển số xe máy (gửi xe)</label>
                                    <input type="text" name="bienSoXe" id="bienSoXeSua" placeholder="Ví dụ: 59-X1 123.45"
                                        class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                </div>

                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <label for="nguoiLienHeKhanCapSua" class="block font-medium text-zinc-700 mb-1">Người thân khẩn cấp</label>
                                        <input type="text" name="nguoiLienHeKhanCap" id="nguoiLienHeKhanCapSua" placeholder="Họ tên người thân"
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>

                                    <div>
                                        <label for="sdtKhanCapSua" class="block font-medium text-zinc-700 mb-1">SĐT người thân</label>
                                        <input type="text" name="sdtKhanCap" id="sdtKhanCapSua" placeholder="0912..."
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 tabular-nums">
                                    </div>

                                    <div>
                                        <label for="quanHeKhanCapSua" class="block font-medium text-zinc-700 mb-1">Mối quan hệ</label>
                                        <input type="text" name="quanHeKhanCap" id="quanHeKhanCapSua" placeholder="Bố/Mẹ/Anh..."
                                            class="w-full px-3 py-2 bg-white border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900">
                                    </div>
                                </div>
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
                        Cập nhật hồ sơ
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

            <form id="bieuMauCapTaiKhoan" action="" method="POST">
                @csrf
                <div class="space-y-4 text-xs">
                    <p class="text-zinc-500">
                        Cấp quyền đăng nhập cho khách <strong id="nhanTenKhachCapTK" class="text-zinc-900">...</strong> vào cổng tự phục vụ của khách thuê.
                    </p>

                    <div>
                        <label for="emailCapMoi" class="block font-medium text-zinc-700 mb-1">Email đăng nhập <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="emailCapMoi" required placeholder="khach@example.com"
                            class="w-full px-3 py-2 bg-zinc-50 border border-zinc-300 rounded-lg text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:bg-white">
                    </div>

                    <div>
                        <label for="matKhauCapMoi" class="block font-medium text-zinc-700 mb-1">Mật khẩu khởi tạo <span class="text-red-500">*</span></label>
                        <input type="password" name="matKhau" id="matKhauCapMoi" required placeholder="Tối thiểu 6 ký tự"
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
                        Xác nhận cấp
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL CHI TIẾT KHÁCH THUÊ -->
    <div id="modalChiTietKhach" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-lg w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100 mb-4">
                <h3 id="chiTietTenKhach" class="text-sm font-semibold text-zinc-900">Chi tiết khách thuê</h3>
                <button type="button" data-dong-modal class="text-zinc-400 hover:text-zinc-700 text-base">&times;</button>
            </div>

            <div id="noiDungChiTietKhach">
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
    <div id="modalXacNhanXoaKhach" class="hop-thoai-modal modal-an fixed inset-0 z-50 bg-zinc-900/50 backdrop-blur-xs items-center justify-center p-4">
        <div class="chuyen-dong-modal bg-white rounded-xl border border-zinc-200 shadow-xl max-w-sm w-full p-6 text-center">
            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-zinc-900 mb-1">Xóa hồ sơ khách thuê?</h3>
            <p class="text-xs text-zinc-500 mb-4">
                Bạn có chắc chắn muốn ngưng hoạt động hồ sơ của <strong id="nhanTenKhachXoa" class="text-zinc-900">...</strong>? Dữ liệu lịch sử hợp đồng và thanh toán vẫn được bảo toàn.
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
                        Xác nhận xóa
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
