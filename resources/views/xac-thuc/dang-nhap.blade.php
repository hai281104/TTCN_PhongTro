<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Quản Lý Nhà Trọ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans antialiased text-zinc-900 bg-zinc-50 flex items-center justify-center p-4 selection:bg-zinc-900 selection:text-white">
    <div class="w-full max-w-[420px]">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-zinc-900 text-white shadow-sm mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900">Quản Lý Nhà Trọ</h1>
            <p class="text-sm text-zinc-500 mt-1">Đăng nhập để quản lý phòng trọ và dịch vụ</p>
        </div>

        <div class="bg-white border border-zinc-200 rounded-xl shadow-xs p-7 sm:p-8">
            @if (session('thongBaoThanhCong'))
                <div class="mb-5 p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('thongBaoThanhCong') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div class="flex-1">
                        @foreach ($errors->all() as $loi)
                            <p>{{ $loi }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('dang-nhap.xu-ly') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-700 mb-1.5">Địa chỉ Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="chutro@example.com"
                            class="block w-full pl-10 pr-3.5 py-2.5 text-sm bg-white border @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-zinc-300 focus:border-zinc-900 focus:ring-zinc-900 @enderror rounded-lg text-zinc-900 placeholder:text-zinc-400 transition-colors focus:outline-none focus:ring-1">
                    </div>
                </div>

                <div>
                    <label for="matKhau" class="block text-sm font-medium text-zinc-700 mb-1.5">Mật khẩu</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <input type="password" id="matKhau" name="matKhau" required
                            placeholder="••••••••"
                            class="block w-full pl-10 pr-10 py-2.5 text-sm bg-white border @error('matKhau') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-zinc-300 focus:border-zinc-900 focus:ring-zinc-900 @enderror rounded-lg text-zinc-900 placeholder:text-zinc-400 transition-colors focus:outline-none focus:ring-1">
                        <button type="button" id="nutAnHienMatKhau" aria-label="Ẩn hiện mật khẩu"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-700 transition-colors">
                            <svg id="bieuTuongHien" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg id="bieuTuongAn" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="ghiNho" value="1"
                            class="w-4 h-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900 cursor-pointer">
                        <span class="text-sm text-zinc-600">Ghi nhớ đăng nhập</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white font-medium text-sm transition-colors shadow-xs">
                        <span>Đăng nhập</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 p-4 rounded-xl border border-zinc-200 bg-white/70 backdrop-blur-sm shadow-xs text-xs text-zinc-600 space-y-2.5">
            <div class="font-medium text-zinc-800 flex items-center justify-between">
                <span>Tài khoản kiểm thử nhanh (Mật khẩu: 123456)</span>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="dienTaiKhoan('chutro@example.com', '123456')"
                    class="p-2 rounded-lg border border-zinc-200 bg-zinc-50 hover:bg-zinc-100 text-left transition-colors">
                    <div class="font-medium text-zinc-900">Chủ trọ</div>
                    <div class="text-[11px] text-zinc-500 truncate">chutro@example.com</div>
                </button>
                <button type="button" onclick="dienTaiKhoan('nguyenvana@example.com', '123456')"
                    class="p-2 rounded-lg border border-zinc-200 bg-zinc-50 hover:bg-zinc-100 text-left transition-colors">
                    <div class="font-medium text-zinc-900">Khách thuê</div>
                    <div class="text-[11px] text-zinc-500 truncate">nguyenvana@example.com</div>
                </button>
            </div>
        </div>
    </div>

    <script>
        const nutAnHien = document.getElementById('nutAnHienMatKhau');
        const oMatKhau = document.getElementById('matKhau');
        const bieuTuongHien = document.getElementById('bieuTuongHien');
        const bieuTuongAn = document.getElementById('bieuTuongAn');

        nutAnHien.addEventListener('click', function () {
            if (oMatKhau.type === 'password') {
                oMatKhau.type = 'text';
                bieuTuongHien.classList.add('hidden');
                bieuTuongAn.classList.remove('hidden');
            } else {
                oMatKhau.type = 'password';
                bieuTuongHien.classList.remove('hidden');
                bieuTuongAn.classList.add('hidden');
            }
        });

        function dienTaiKhoan(email, matKhau) {
            document.getElementById('email').value = email;
            document.getElementById('matKhau').value = matKhau;
        }
    </script>
</body>
</html>
