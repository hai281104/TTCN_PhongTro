<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng Khách Thuê - Quản Lý Nhà Trọ</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    boxShadow: { 'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.05)' }
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans antialiased text-zinc-900 bg-zinc-50">
    <div class="min-h-full">
        <header class="bg-white border-b border-zinc-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-zinc-900 text-white flex items-center justify-center font-bold text-sm">
                        KT
                    </div>
                    <div>
                        <h1 class="text-base font-semibold text-zinc-900">Quản Lý Nhà Trọ</h1>
                        <p class="text-xs text-zinc-500">Cổng Dành Cho Khách Thuê</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-medium text-zinc-900">{{ Auth::user()->email }}</div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-sky-50 text-sky-700 border border-sky-200">
                            Khách thuê
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
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs mb-6">
                <h2 class="text-lg font-semibold text-zinc-900 mb-2">Xin chào!</h2>
                <p class="text-sm text-zinc-600">Bạn đã đăng nhập thành công với vai trò <strong class="text-zinc-900">KHACH_THUE</strong>.</p>
            </div>

            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs">
                <h3 class="text-sm font-semibold text-zinc-900 mb-3">Thông tin hợp đồng và phòng thuê của bạn</h3>
                @php
                    $khachThue = Auth::user()->khachThue;
                    $hopDong = $khachThue ? $khachThue->hopDongHieuLuc : null;
                @endphp

                @if ($khachThue && $hopDong)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="p-3.5 bg-zinc-50 rounded-lg border border-zinc-200">
                            <div class="text-xs text-zinc-500">Họ và tên</div>
                            <div class="font-medium text-zinc-900 mt-0.5">{{ $khachThue->hoTen }}</div>
                        </div>
                        <div class="p-3.5 bg-zinc-50 rounded-lg border border-zinc-200">
                            <div class="text-xs text-zinc-500">Phòng đang thuê</div>
                            <div class="font-medium text-zinc-900 mt-0.5">{{ optional($hopDong->phongTro)->soPhong ?? 'N/A' }}</div>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-zinc-500">Hồ sơ khách thuê hoặc hợp đồng đang được cập nhật.</p>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
