@props(['active'])

@php
    $tabs = [
        ['key' => 'profile', 'route' => 'tai-khoan', 'label' => 'Hồ sơ'],
        ['key' => 'favorites', 'route' => 'yeu-thich', 'label' => 'Yêu thích'],
        ['key' => 'following', 'route' => 'theo-doi', 'label' => 'Theo dõi'],
        ['key' => 'history', 'route' => 'lich-su-doc', 'label' => 'Lịch sử'],
    ];
@endphp

<div class="account-tabs">
    @foreach($tabs as $tab)
        <a href="{{ route($tab['route']) }}" class="{{ $active === $tab['key'] ? 'account-tab account-tab-active' : 'account-tab' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
