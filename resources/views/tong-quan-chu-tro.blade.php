<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Nhà Trọ - Trang Chủ Trọ</title>
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
                        QL
                    </div>
                    <div>
                        <h1 class="text-base font-semibold text-zinc-900">Quản Lý Nhà Trọ</h1>
                        <p class="text-xs text-zinc-500">Cổng Quản Trị Chủ Trọ</p>
                    </div>
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

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white border border-zinc-200 rounded-xl p-6 shadow-xs mb-6">
                <h2 class="text-lg font-semibold text-zinc-900 mb-2">Xin chào, Chủ trọ!</h2>
                <p class="text-sm text-zinc-600">Bạn đã đăng nhập thành công vào hệ thống quản lý nhà trọ với vai trò <strong class="text-zinc-900">CHU_TRO</strong>.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                    <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Tổng số phòng</div>
                    <div class="text-2xl font-bold text-zinc-900 mt-2">{{ \App\Models\PhongTro::count() }}</div>
                </div>

                <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                    <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Phòng đang thuê</div>
                    <div class="text-2xl font-bold text-sky-700 mt-2">{{ \App\Models\PhongTro::where('trangThaiPhong', 'DANG_THUE')->count() }}</div>
                </div>

                <div class="bg-white border border-zinc-200 rounded-xl p-5 shadow-xs">
                    <div class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Phòng trống</div>
                    <div class="text-2xl font-bold text-emerald-700 mt-2">{{ \App\Models\PhongTro::where('trangThaiPhong', 'TRONG')->count() }}</div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
