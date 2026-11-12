@if ($paginator->total() > 0)
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-4 py-2.5 bg-white border border-zinc-200 rounded-xl text-xs">
        <div class="text-zinc-600 whitespace-nowrap">
            Hiển thị <strong class="text-zinc-900 tabular-nums">{{ $paginator->firstItem() }}</strong> &ndash;
            <strong class="text-zinc-900 tabular-nums">{{ $paginator->lastItem() }}</strong>
            trong tổng số <strong class="text-zinc-900 tabular-nums">{{ $paginator->total() }}</strong> dòng
            (Trang <span class="font-semibold text-zinc-900 tabular-nums">{{ $paginator->currentPage() }}/{{ $paginator->lastPage() }}</span>)
        </div>

        @if ($paginator->hasPages())
            <div class="flex flex-wrap items-center gap-2">
                <!-- Nút điều hướng trang -->
                <div class="inline-flex items-center gap-1">
                    @if ($paginator->onFirstPage())
                        <span class="px-2.5 py-1 rounded border border-zinc-200 text-zinc-300 cursor-not-allowed">&laquo; Trước</span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                            class="px-2.5 py-1 rounded border border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-50 font-medium transition-colors">
                            &laquo; Trước
                        </a>
                    @endif

                    @php
                        $trangHienTai = $paginator->currentPage();
                        $trangCuoi = $paginator->lastPage();
                        $batDau = max(1, $trangHienTai - 2);
                        $ketThuc = min($trangCuoi, $trangHienTai + 2);
                    @endphp

                    @if ($batDau > 1)
                        <a href="{{ $paginator->url(1) }}" class="px-2.5 py-1 rounded border border-zinc-200 bg-white text-zinc-700 hover:bg-zinc-50 tabular-nums">1</a>
                        @if ($batDau > 2)
                            <span class="px-1 text-zinc-400">...</span>
                        @endif
                    @endif

                    @for ($trang = $batDau; $trang <= $ketThuc; $trang++)
                        @if ($trang == $trangHienTai)
                            <span class="px-2.5 py-1 rounded bg-zinc-900 text-white font-semibold tabular-nums">{{ $trang }}</span>
                        @else
                            <a href="{{ $paginator->url($trang) }}"
                                class="px-2.5 py-1 rounded border border-zinc-200 bg-white text-zinc-700 hover:bg-zinc-50 tabular-nums transition-colors">
                                {{ $trang }}
                            </a>
                        @endif
                    @endfor

                    @if ($ketThuc < $trangCuoi)
                        @if ($ketThuc < $trangCuoi - 1)
                            <span class="px-1 text-zinc-400">...</span>
                        @endif
                        <a href="{{ $paginator->url($trangCuoi) }}" class="px-2.5 py-1 rounded border border-zinc-200 bg-white text-zinc-700 hover:bg-zinc-50 tabular-nums">{{ $trangCuoi }}</a>
                    @endif

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                            class="px-2.5 py-1 rounded border border-zinc-300 bg-white text-zinc-700 hover:bg-zinc-50 font-medium transition-colors">
                            Sau &raquo;
                        </a>
                    @else
                        <span class="px-2.5 py-1 rounded border border-zinc-200 text-zinc-300 cursor-not-allowed">Sau &raquo;</span>
                    @endif
                </div>

                <!-- Nhập số trang để chuyển nhanh -->
                <form action="{{ url()->current() }}" method="GET" class="inline-flex items-center gap-1.5 pl-2 border-l border-zinc-200">
                    @foreach (request()->except('page') as $khoa => $giaTri)
                        @if (is_array($giaTri))
                            @foreach ($giaTri as $v)
                                <input type="hidden" name="{{ $khoa }}[]" value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $khoa }}" value="{{ $giaTri }}">
                        @endif
                    @endforeach
                    <span class="text-zinc-500 whitespace-nowrap">Đến trang:</span>
                    <input type="number" name="page" min="1" max="{{ $paginator->lastPage() }}" value="{{ $paginator->currentPage() }}"
                        class="w-14 px-2 py-1 bg-zinc-50 border border-zinc-300 rounded text-center text-zinc-900 tabular-nums focus:outline-none">
                    <button type="submit"
                        class="px-2.5 py-1 rounded bg-zinc-900 text-white font-medium hover:bg-zinc-800 transition-colors">
                        Đi
                    </button>
                </form>
            </div>
        @endif
    </div>
@endif

