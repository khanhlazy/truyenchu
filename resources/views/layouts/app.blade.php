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
        $siteName = \App\Models\CauHinh::lay('ten_website', 'Đam Mê Truyện');
        $siteDescription = \App\Models\CauHinh::lay('mo_ta_website', 'Website đọc truyện chữ online miễn phí with kho truyện được cập nhật liên tục.');
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
<body x-data="uiShell()" @keydown.escape.window="closeAll()" class="min-h-screen antialiased pb-20 lg:pb-0 @hasSection('mobile_app_home') !pb-0 @endif">
    <header class="sticky top-0 z-50 border-b glass-panel @hasSection('mobile_app_home') hidden lg:block @endif" style="border-color: var(--ui-border);">
        <div x-ref="headerShell" class="shell-container">
            <div class="flex h-16 items-center gap-3">
                <a href="{{ route('trang-chu') }}" class="flex items-center gap-2 shrink-0">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-white shadow-card" style="background: var(--ui-gradient-highlight);">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </span>
                    <span class="text-base font-bold tracking-normal sm:text-lg" style="color: var(--ui-text);">Đam Mê <span style="color: var(--ui-primary);">Truyện</span></span>
                </a>

                {{-- Desktop nav links --}}
                <nav class="hidden lg:flex items-center gap-1 ml-6">
                    <a href="{{ route('trang-chu') }}" class="nav-link">Trang chủ</a>
                    <a href="{{ route('truyen.danh-sach') }}" class="nav-link">Danh sách</a>
                    <button type="button" @click.prevent.stop="toggleDropdown('genres')" class="nav-link">
                        Thể loại
                        <svg class="h-3.5 w-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <a href="{{ route('tim-kiem') }}" class="nav-link">Tìm kiếm</a>
                    @if($donateEnabled)
                        <a href="{{ route('donate') }}" class="nav-link">Ủng hộ</a>
                    @endif
                </nav>

                {{-- Search --}}
                <form action="{{ route('tim-kiem') }}" method="GET" class="hidden lg:block flex-1 max-w-md ml-auto mr-3">
                    <div class="relative">
                        <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" placeholder="Tìm truyện, tác giả..."
                               class="field-shell !min-h-10 !py-2 pl-10 pr-9 text-sm" style="background: var(--ui-surface);">
                        <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2" style="color: var(--ui-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.2-5.2m0 0A7.3 7.3 0 105.5 5.5a7.3 7.3 0 0010.3 10.3z" />
                        </svg>
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
                        <svg class="h-4 w-4 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg class="h-4 w-4 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>

                    @auth
                        <div class="relative">
                            <button type="button" @click.prevent.stop="toggleDropdown('account')" class="flex items-center gap-2 rounded-lg p-1.5 hover:bg-[color:var(--ui-surface-variant)]">
                                <img src="{{ auth()->user()->urlAnhDaiDien() }}" alt="{{ auth()->user()->ten_hien_thi }}" class="h-8 w-8 rounded-full object-cover">
                                <span class="hidden sm:block max-w-[100px] truncate text-sm font-medium">{{ auth()->user()->ten_hien_thi }}</span>
                            </button>

                            <template x-if="activeDropdown === 'account'">
                                <div x-cloak @click.stop @click.outside="closeDropdown('account')" x-transition class="absolute right-0 top-full mt-2 w-56 p-2 surface-panel ring-1 ring-black/5">
                                    <div class="text-sm">
                                        <a href="{{ route('tai-khoan') }}" @click="closeAll()" class="flex px-3 py-2 rounded-md hover:bg-[color:var(--ui-surface-variant)] font-medium">Tài khoản</a>
                                        <a href="{{ route('yeu-thich') }}" @click="closeAll()" class="flex px-3 py-2 rounded-md hover:bg-[color:var(--ui-surface-variant)] font-medium">Yêu thích</a>
                                        <a href="{{ route('lich-su-doc') }}" @click="closeAll()" class="flex px-3 py-2 rounded-md hover:bg-[color:var(--ui-surface-variant)] font-medium">Lịch sử</a>
                                        @if(auth()->user()->laAdmin())
                                            <a href="{{ route('admin.dashboard') }}" @click="closeAll()" class="flex px-3 py-2 rounded-md hover:bg-[color:var(--ui-surface-variant)] font-semibold mt-1 pt-2 border-t" style="color: var(--ui-primary); border-color: var(--ui-border);">Bảng điều khiển</a>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('dang-xuat') }}" class="mt-1 border-t pt-1" style="border-color: var(--ui-border);">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-[color:var(--ui-surface-variant)]" style="color: var(--ui-danger);">Đăng xuất</button>
                                    </form>
                                </div>
                            </template>
                        </div>
                    @else
                        <a href="{{ route('dang-nhap') }}" class="btn-primary text-xs px-4 py-2">Đăng nhập</a>
                    @endauth

                    <button type="button" @click.prevent.stop="toggleMobileMenu()" class="icon-button lg:hidden">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                {{-- Genre dropdown (Moved inside headerShell) --}}
                <template x-if="activeDropdown === 'genres'">
                    <div x-cloak class="absolute left-0 top-full w-full border-t z-50 bg-[color:var(--ui-surface)] shadow-lg" style="border-color: var(--ui-border);" @click.outside="closeDropdown('genres')">
                        <div class="shell-container py-6">
                            <div @click.stop x-transition>
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-sm font-bold uppercase tracking-wider" style="color: var(--ui-text);">Thể loại truyện</h3>
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
            </div>
        </div>

        {{-- Mobile menu --}}
        <template x-if="mobileMenuOpen">
            <div x-cloak class="lg:hidden">
                <div class="fixed inset-0 z-[70]">
                    <button type="button" class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="closeAll()"></button>
                    <div class="absolute inset-x-4 top-4 rounded-xl p-4 shadow-xl" style="background: var(--ui-surface); border: 1px solid var(--ui-border);" x-transition>
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

    <main class="relative z-10 flex-1 py-6 @hasSection('mobile_app_home') py-0 lg:py-6 @endif">
        @yield('content')
    </main>

    <footer class="mt-8 border-t py-8 @hasSection('mobile_app_home') hidden lg:block @endif" style="border-color: var(--ui-border); background: var(--ui-surface);">
        <div class="shell-container">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <h3 class="text-xl font-semibold" style="color: var(--ui-text);">{{ mb_strtoupper($siteName) }}</h3>
                    <p class="mt-4 text-sm leading-relaxed" style="color: var(--ui-muted);">
                        Đọc truyện online, truyện chữ, truyện full, truyện hay. Tổng hợp đầy đủ các thể loại truyện từ ngôn tình, kiếm hiệp, tiên hiệp, huyền huyễn, đô thị, linh dị,...
                    </p>
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

    @if(!request()->routeIs('chuong.doc'))
        <nav class="fixed inset-x-3 bottom-3 z-50 grid grid-cols-4 gap-1 rounded-xl border p-1 shadow-overlay backdrop-blur-xl lg:hidden @hasSection('mobile_app_home') hidden @endif"
             style="border-color: var(--ui-border); background: color-mix(in srgb, var(--ui-surface) 86%, transparent);">
            @php
                $bottomNav = [
                    [
                        'label' => 'Trang chủ',
                        'url' => route('trang-chu'),
                        'active' => request()->routeIs('trang-chu'),
                        'icon' => 'M3 12l9-9 9 9M5 10v10h14V10',
                    ],
                    [
                        'label' => 'Thư viện',
                        'url' => route('truyen.danh-sach'),
                        'active' => request()->routeIs('truyen.*', 'the-loai.*', 'chuong.doc'),
                        'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                    ],
                    [
                        'label' => 'Tìm kiếm',
                        'url' => route('tim-kiem'),
                        'active' => request()->routeIs('tim-kiem'),
                        'icon' => 'M21 21l-5.2-5.2m0 0A7.3 7.3 0 105.5 5.5a7.3 7.3 0 0010.3 10.3z',
                    ],
                    [
                        'label' => 'Tài khoản',
                        'url' => auth()->check() ? route('tai-khoan') : route('dang-nhap'),
                        'active' => request()->routeIs('tai-khoan*', 'yeu-thich', 'lich-su-doc', 'theo-doi', 'dang-nhap', 'dang-ky', 'quen-mat-khau', 'dat-lai-mat-khau'),
                        'icon' => 'M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0',
                    ],
                ];
            @endphp

            @foreach($bottomNav as $item)
                <a href="{{ $item['url'] }}" class="flex min-w-0 min-h-12 flex-col items-center justify-center gap-1 rounded-lg text-[11px] font-medium {{ $item['active'] ? 'nav-link-active' : '' }}" style="color: {{ $item['active'] ? 'var(--ui-primary)' : 'var(--ui-muted)' }};">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    @endif

    @stack('scripts')
</body>
</html>
