<!DOCTYPE html>
<html lang="vi" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: window.innerWidth > 768 }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Quản trị Hệ thống</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        [x-cloak] { display: none !important; }
        :root {
            --admin-sidebar-bg: #0f172a;
            --admin-sidebar-active: #6366f1;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen transition-colors duration-300">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'w-72' : 'w-0 -translate-x-full lg:w-20 lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 flex flex-col bg-slate-900 text-white transition-all duration-300 shadow-2xl lg:shadow-none">
            
            {{-- Brand --}}
            <div class="flex h-20 items-center gap-3 border-b border-white/5 px-6 shrink-0 overflow-hidden">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 shadow-lg shadow-indigo-600/20">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div x-show="sidebarOpen" x-transition class="min-w-0">
                    <h1 class="truncate text-sm font-bold uppercase tracking-widest text-white">DAMMETRUYEN</h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Hệ thống quản trị</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-1 overflow-y-auto p-4 scrollbar-hide">
                @php
                    $menu = [
                        ['route' => 'admin.dashboard', 'label' => 'Bảng điều khiển', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['route' => 'admin.truyen.danh-sach', 'label' => 'Quản lý truyện', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ['route' => 'admin.the-loai.danh-sach', 'label' => 'Thể loại', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                        ['route' => 'admin.binh-luan.danh-sach', 'label' => 'Bình luận', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                        ['route' => 'admin.nguoi-dung.danh-sach', 'label' => 'Người dùng', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                        ['route' => 'admin.chat.danh-sach', 'label' => 'Chat', 'icon' => 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z'],
                        ['route' => 'admin.crawler.index', 'label' => 'Công cụ cào', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ];
                @endphp

                @foreach($menu as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all group
                              {{ request()->routeIs($item['route'] . '*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"></path></svg>
                        <span x-show="sidebarOpen" class="whitespace-nowrap">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            {{-- Quick Stats --}}
            <div x-show="sidebarOpen" class="p-4 border-t border-white/5 bg-slate-950/30">
                <div class="rounded-2xl bg-indigo-500/10 p-4 ring-1 ring-indigo-500/20">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400">Trạng thái hệ thống</p>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-300">Bình thường</span>
                        <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Footer --}}
            <div class="border-t border-white/5 p-4 shrink-0 overflow-hidden bg-slate-950/50">
                <a href="{{ route('trang-chu') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Về trang chủ</span>
                </a>
            </div>
        </aside>

        {{-- Main Wrapper --}}
        <div :class="sidebarOpen ? 'lg:ml-72' : 'lg:ml-20'" class="flex-1 flex flex-col transition-all duration-300 min-w-0">
            {{-- Header --}}
            <header class="h-20 flex items-center justify-between px-6 bg-white/80 dark:bg-slate-900/80 border-b border-slate-200 dark:border-slate-800 backdrop-blur-xl sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 transition-all">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="hidden sm:block">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">@yield('page_title', 'Bảng quản trị')</h2>
                        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-0.5">
                            <span class="text-indigo-600">Admin</span>
                            <span>/</span>
                            <span>@yield('title')</span>
                        </nav>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 transition-all">
                        <svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    <div class="h-8 w-[1px] bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>

                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-900 dark:text-white">{{ auth()->user()->ten_hien_thi }}</p>
                            <p class="text-[10px] font-bold text-indigo-500 uppercase">Administrator</p>
                        </div>
                        <img src="{{ auth()->user()->urlAnhDaiDien() }}" alt="Avatar" class="h-10 w-10 rounded-xl object-cover ring-2 ring-indigo-500/20">
                        
                        <form method="POST" action="{{ route('dang-xuat') }}">
                            @csrf
                            <button type="submit" class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            <div class="px-8 mt-6">
                @if(session('thanh_cong'))
                    <div class="rounded-2xl bg-green-500/10 p-4 border border-green-500/20 text-green-600 text-sm font-medium animate-fade-in flex items-center gap-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('thanh_cong') }}
                    </div>
                @endif
                @if(session('loi'))
                    <div class="rounded-2xl bg-red-500/10 p-4 border border-red-500/20 text-red-600 text-sm font-medium animate-fade-in flex items-center gap-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('loi') }}
                    </div>
                @endif
            </div>

            {{-- Main Content --}}
            <main class="flex-1 p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

