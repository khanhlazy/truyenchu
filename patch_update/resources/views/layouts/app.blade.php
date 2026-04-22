<!DOCTYPE html>
<html lang="vi" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TruyệnChữ - Đọc Truyện Online')</title>
    <meta name="description" content="@yield('meta_description', 'TruyệnChữ - Website đọc truyện chữ online miễn phí, truyện hay cập nhật liên tục.')">

    @yield('meta_seo')

    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'TruyệnChữ - Đọc Truyện Online')">
    <meta property="og:description" content="@yield('meta_description', 'TruyệnChữ - Website đọc truyện chữ online miễn phí.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen flex flex-col transition-colors duration-300">

    {{-- Header --}}
    <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('trang-chu') }}" class="flex items-center gap-2">
                    @php $siteLogo = \App\Models\CauHinh::urlLogo(); $siteName = \App\Models\CauHinh::lay('ten_website', 'TruyệnChữ'); @endphp
                    @if($siteLogo)
                        <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="h-12 w-auto">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent">{{ $siteName }}</span>
                    @endif
                </a>

                {{-- Navigation --}}
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('truyen.danh-sach') }}" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition">Danh sách</a>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center gap-1">
                            Thể loại
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-cloak x-show="open" @click.outside="open = false" x-transition
                             class="absolute left-0 mt-2 w-[480px] bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 p-4">
                            <div class="grid grid-cols-3 gap-x-4 gap-y-1">
                                @php $layoutTheLoais = \Illuminate\Support\Facades\Cache::remember('danh_sach_the_loai', 3600, fn() => \App\Models\TheLoai::sapXep()->get()); @endphp
                                @foreach($layoutTheLoais as $tl)
                                    <a href="{{ route('the-loai.danh-sach', $tl->slug) }}" class="text-sm px-3 py-2 rounded-lg hover:bg-indigo-50 dark:hover:bg-gray-700 hover:text-indigo-600 dark:hover:text-indigo-400 transition">{{ $tl->ten }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('tim-kiem') }}" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition">Tìm kiếm</a>
                    @auth
                        <a href="{{ route('chat') }}" class="text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition">Chat</a>
                    @endauth
                </nav>

                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    {{-- Search toggle --}}
                    <form action="{{ route('tim-kiem') }}" method="GET" class="hidden sm:flex items-center">
                        <div class="relative">
                            <input type="text" name="tu_khoa" placeholder="Tìm truyện..."
                                   class="w-44 lg:w-64 pl-9 pr-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </form>

                    {{-- Dark mode toggle --}}
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    {{-- User menu --}}
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <img src="{{ auth()->user()->urlAnhDaiDien() }}" alt="{{ auth()->user()->ten_hien_thi }}"
                                     class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-600">
                                <span class="hidden md:block text-sm font-medium">{{ auth()->user()->ten_hien_thi }}</span>
                            </button>
                            <div x-cloak x-show="open" @click.outside="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
                                <a href="{{ route('tai-khoan') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700">Tài khoản</a>
                                <a href="{{ route('yeu-thich') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700">Yêu thích</a>
                                <a href="{{ route('theo-doi') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700">Theo dõi</a>
                                <a href="{{ route('lich-su-doc') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700">Lịch sử đọc</a>
                                @if(auth()->user()->laAdmin())
                                    <hr class="my-1 border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-indigo-600 dark:text-indigo-400 hover:bg-gray-50 dark:hover:bg-gray-700">Quản trị</a>
                                @endif
                                <hr class="my-1 border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('dang-xuat') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50 dark:hover:bg-gray-700">Đăng xuất</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('dang-nhap') }}" class="text-sm font-medium px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Đăng nhập</a>
                    @endauth

                    {{-- Mobile menu toggle --}}
                    <button x-data @click="$dispatch('toggle-mobile-menu')" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-cloak x-data="{ open: false }" @toggle-mobile-menu.window="open = !open" x-show="open" x-transition
             class="md:hidden border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 space-y-2">
            <form action="{{ route('tim-kiem') }}" method="GET" class="mb-3">
                <input type="text" name="tu_khoa" placeholder="Tìm truyện..."
                       class="w-full px-4 py-2 text-sm bg-gray-100 dark:bg-gray-700 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500">
            </form>
            <a href="{{ route('truyen.danh-sach') }}" class="block py-2 text-sm font-medium">Danh sách truyện</a>
            <a href="{{ route('tim-kiem') }}" class="block py-2 text-sm font-medium">Tìm kiếm</a>
            @auth
                <a href="{{ route('chat') }}" class="block py-2 text-sm font-medium">Chat</a>
            @endauth
        </div>
    </header>

    {{-- Flash messages --}}
    @if(session('thanh_cong'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg p-4 text-green-800 dark:text-green-300 text-sm">
                {{ session('thanh_cong') }}
            </div>
        </div>
    @endif
    @if(session('loi'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4 text-red-800 dark:text-red-300 text-sm">
                {{ session('loi') }}
            </div>
        </div>
    @endif

    {{-- Main content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md">
                        {{ \App\Models\CauHinh::lay('mo_ta_website', 'Website đọc truyện chữ online miễn phí với hàng nghìn bộ truyện hay được cập nhật liên tục.') }}
                    </p>
                </div>
                <div>
                    <h3 class="font-semibold mb-3 text-sm uppercase tracking-wider">Liên kết</h3>
                    <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                        <li><a href="{{ route('truyen.danh-sach') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Danh sách truyện</a></li>
                        <li><a href="{{ route('tim-kiem') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Tìm kiếm</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-3 text-sm uppercase tracking-wider">Thể loại hot</h3>
                    <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                        @foreach($layoutTheLoais->take(5) as $tl)
                            <li><a href="{{ route('the-loai.danh-sach', $tl->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">{{ $tl->ten }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700 mt-8 pt-6 text-center text-sm text-gray-400">
                © {{ date('Y') }} TruyệnChữ. Tất cả quyền được bảo lưu.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
