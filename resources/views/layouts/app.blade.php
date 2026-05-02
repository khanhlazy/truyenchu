<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Merriweather:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
    @php
        $siteName = \App\Models\CauHinh::lay('ten_website', 'Truyện Chữ');
        $siteDescription = \App\Models\CauHinh::lay('mo_ta_website', 'Website đọc truyện chữ online miễn phí với kho truyện được cập nhật liên tục.');
        $siteLogo = \App\Models\CauHinh::urlLogo();
        $favicon = \App\Models\CauHinh::lay('favicon');
        $categoryMenu = \App\Models\TheLoai::sapXep()->get();
        $footerCategories = $categoryMenu->take(12);
        $donateEnabled = \App\Models\CauHinh::lay('donate_bat', '0') === '1';
    @endphp

    <title>@yield('title', $siteName . ' - Đọc Truyện Online')</title>
    <meta name="description" content="@yield('meta_description', $siteDescription)">

    @yield('meta_seo')

    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', $siteName . ' - Đọc Truyện Online')">
    <meta property="og:description" content="@yield('meta_description', $siteDescription)">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">

    @if($favicon)
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('storage/' . $favicon) }}">
    @endif

    <script>
        document.documentElement.classList.toggle('dark', localStorage.getItem('darkMode') === 'true');
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body x-data="uiShell()" @keydown.escape.window="closeAll()" class="min-h-screen antialiased">
    <header class="sticky top-0 z-50 border-b" style="border-color: var(--ui-border); background: var(--ui-surface);">
        <div x-ref="headerShell" class="shell-container">
            <div class="flex h-14 items-center gap-3">
                {{-- Logo --}}
                <a href="{{ route('trang-chu') }}" class="flex items-center gap-2 shrink-0">
                    @if($siteLogo)
                        <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="h-8 w-auto object-contain">
                    @endif
                    <span class="text-lg font-bold" style="color: var(--ui-text);">{{ $siteName }}</span>
                </a>

                {{-- Desktop nav links --}}
                <nav class="hidden lg:flex items-center gap-1 ml-6">
                    <a href="{{ route('trang-chu') }}" class="nav-link">Trang chủ</a>
                    <a href="{{ route('truyen.danh-sach') }}" class="nav-link">Danh sách</a>
                    <button type="button" @click.stop="toggleDropdown('genres')" class="nav-link">
                        Thể loại
                        <svg class="h-3.5 w-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <a href="{{ route('tim-kiem') }}" class="nav-link">Tìm kiếm</a>
                    @if($donateEnabled)
                        <a href="{{ route('donate') }}" class="nav-link">Ủng hộ</a>
                    @endif
                </nav>

                {{-- Search --}}
                <form action="{{ route('tim-kiem') }}" method="GET" class="hidden lg:block flex-1 max-w-sm ml-auto mr-3">
                    <div class="relative">
                        <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" placeholder="Tìm truyện, tác giả..."
                               class="field-shell !py-2 pl-3.5 pr-9 text-sm" style="background: var(--ui-surface-muted);">
                        <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2" style="color: var(--ui-muted);">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.2-5.2m0 0A7.3 7.3 0 105.5 5.5a7.3 7.3 0 0010.3 10.3z" />
                            </svg>
                        </button>
                    </div>
                </form>

                {{-- Right side --}}
                <div class="ml-auto lg:ml-0 flex items-center gap-2">
                    {{-- Dark mode toggle --}}
                    <button type="button" @click="toggleTheme()" class="icon-button" title="Chế độ tối">
                        <svg x-show="!$store.theme.darkMode" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="$store.theme.darkMode" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>

                    @auth
                        <div class="relative">
                            <button type="button" @click.stop="toggleDropdown('account')" class="flex items-center gap-2 p-1 rounded-lg hover:bg-[color:var(--ui-surface-muted)]">
                                <img src="{{ auth()->user()->urlAnhDaiDien() }}" alt="{{ auth()->user()->ten_hien_thi }}" class="h-7 w-7 rounded-full object-cover">
                                <span class="hidden sm:block max-w-[100px] truncate text-sm font-medium">{{ auth()->user()->ten_hien_thi }}</span>
                            </button>

                            <template x-if="activeDropdown === 'account'">
                                <div @click.stop x-transition class="absolute right-0 top-full mt-1.5 w-52 p-1 surface-panel ring-1 ring-black/5">
                                    <div class="text-sm">
                                        <a href="{{ route('tai-khoan') }}" @click="closeAll()" class="flex px-3 py-2 rounded-md hover:bg-[color:var(--ui-surface-muted)] font-medium">Tài khoản</a>
                                        <a href="{{ route('yeu-thich') }}" @click="closeAll()" class="flex px-3 py-2 rounded-md hover:bg-[color:var(--ui-surface-muted)] font-medium">Yêu thích</a>
                                        <a href="{{ route('lich-su-doc') }}" @click="closeAll()" class="flex px-3 py-2 rounded-md hover:bg-[color:var(--ui-surface-muted)] font-medium">Lịch sử</a>
                                        @if(auth()->user()->laAdmin())
                                            <a href="{{ route('admin.dashboard') }}" @click="closeAll()" class="flex px-3 py-2 rounded-md hover:bg-[color:var(--ui-surface-muted)] font-semibold mt-1 pt-2 border-t" style="color: var(--ui-primary); border-color: var(--ui-border);">Bảng điều khiển</a>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('dang-xuat') }}" class="mt-1 border-t pt-1" style="border-color: var(--ui-border);">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-red-50 dark:hover:bg-red-950/20" style="color: var(--ui-danger);">Đăng xuất</button>
                                    </form>
                                </div>
                            </template>
                        </div>
                    @else
                        <a href="{{ route('dang-nhap') }}" class="btn-primary text-xs px-4 py-2">Đăng nhập</a>
                    @endauth

                    <button type="button" @click="toggleMobileMenu()" class="icon-button lg:hidden">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Genre dropdown --}}
        <template x-if="activeDropdown === 'genres'">
            <div class="hidden lg:block border-t" style="border-color: var(--ui-border);">
                <div class="shell-container py-4">
                    <div @click.stop x-transition>
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold" style="color: var(--ui-text);">Thể loại truyện</h3>
                            <a href="{{ route('truyen.danh-sach') }}" @click="closeAll()" class="btn-quiet text-xs">Xem tất cả</a>
                        </div>
                        <div class="genre-chip-grid">
                            @foreach($categoryMenu as $category)
                                <a href="{{ route('the-loai.danh-sach', $category->slug) }}" @click="closeAll()" class="genre-chip">{{ $category->ten }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Mobile menu --}}
        <template x-if="mobileMenuOpen">
            <div class="lg:hidden">
                <div class="fixed inset-0 z-[70]">
                    <button type="button" class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="closeAll()"></button>
                    <div class="absolute inset-x-0 top-0 p-4 shadow-xl" style="background: var(--ui-surface); border-bottom: 1px solid var(--ui-border);" x-transition>
                        <div class="space-y-4">
                            <form action="{{ route('tim-kiem') }}" method="GET">
                                <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" placeholder="Tìm truyện, tác giả..." class="field-shell">
                            </form>

                            <nav class="flex flex-col">
                                <a href="{{ route('trang-chu') }}" @click="closeAll()" class="py-2.5 px-1 text-sm font-medium border-b" style="border-color: var(--ui-border);">Trang chủ</a>
                                <a href="{{ route('truyen.danh-sach') }}" @click="closeAll()" class="py-2.5 px-1 text-sm font-medium border-b" style="border-color: var(--ui-border);">Danh sách truyện</a>
                                <a href="{{ route('tim-kiem') }}" @click="closeAll()" class="py-2.5 px-1 text-sm font-medium border-b" style="border-color: var(--ui-border);">Tìm kiếm</a>
                                @auth
                                    <a href="{{ route('chat') }}" @click="closeAll()" class="py-2.5 px-1 text-sm font-medium border-b" style="border-color: var(--ui-border);">Cộng đồng</a>
                                @endauth
                                @if($donateEnabled)
                                    <a href="{{ route('donate') }}" @click="closeAll()" class="py-2.5 px-1 text-sm font-medium">Ủng hộ</a>
                                @endif
                            </nav>

                            <div class="border-t pt-3" style="border-color: var(--ui-border);">
                                @auth
                                    <div class="flex items-center gap-3 mb-3">
                                        <img src="{{ auth()->user()->urlAnhDaiDien() }}" alt="{{ auth()->user()->ten_hien_thi }}" class="h-9 w-9 rounded-full object-cover">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold">{{ auth()->user()->ten_hien_thi }}</p>
                                            <p class="truncate text-xs" style="color: var(--ui-muted);">{{ auth()->user()->email }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('tai-khoan') }}" @click="closeAll()" class="btn-secondary text-xs justify-center">Tài khoản</a>
                                        <a href="{{ route('yeu-thich') }}" @click="closeAll()" class="btn-secondary text-xs justify-center">Yêu thích</a>
                                    </div>
                                    <form method="POST" action="{{ route('dang-xuat') }}" class="mt-3">
                                        @csrf
                                        <button type="submit" class="w-full py-2.5 text-sm font-semibold" style="color: var(--ui-danger);">Đăng xuất</button>
                                    </form>
                                @else
                                    <a href="{{ route('dang-nhap') }}" @click="closeAll()" class="btn-primary w-full justify-center text-sm">Đăng nhập</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </header>

    @if(session('thanh_cong'))
        <div class="shell-container mt-4">
            <div class="surface-panel px-4 py-3 text-sm font-medium" style="color: var(--ui-success);">
                {{ session('thanh_cong') }}
            </div>
        </div>
    @endif

    @if(session('loi'))
        <div class="shell-container mt-4">
            <div class="surface-panel px-4 py-3 text-sm font-medium" style="color: var(--ui-danger);">
                {{ session('loi') }}
            </div>
        </div>
    @endif

    <main class="relative z-10 flex-1 py-6">
        @yield('content')
    </main>

    <footer class="mt-8 border-t py-8" style="border-color: var(--ui-border); background: var(--ui-surface);">
        <div class="shell-container">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <h3 class="text-base font-bold" style="color: var(--ui-text);">{{ $siteName }}</h3>
                    <p class="mt-2 text-sm leading-relaxed" style="color: var(--ui-muted);">{{ $siteDescription }}</p>
                </div>

                <div>
                    <h4 class="text-sm font-semibold" style="color: var(--ui-text);">Điều hướng</h4>
                    <div class="mt-2 flex flex-col gap-1.5 text-sm" style="color: var(--ui-muted);">
                        <a href="{{ route('trang-chu') }}" class="hover:underline">Trang chủ</a>
                        <a href="{{ route('truyen.danh-sach') }}" class="hover:underline">Danh sách truyện</a>
                        <a href="{{ route('tim-kiem') }}" class="hover:underline">Tìm kiếm</a>
                        @if($donateEnabled)
                            <a href="{{ route('donate') }}" class="hover:underline">Ủng hộ</a>
                        @endif
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-semibold" style="color: var(--ui-text);">Thể loại phổ biến</h4>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        @foreach($footerCategories as $category)
                            <a href="{{ route('the-loai.danh-sach', $category->slug) }}" class="tag-pill-muted text-xs">{{ $category->ten }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t text-xs text-center" style="border-color: var(--ui-border); color: var(--ui-muted);">
                © {{ date('Y') }} {{ $siteName }}. Các tác phẩm thuộc bản quyền của tác giả.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
