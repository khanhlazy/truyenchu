@props(['label' => 'Chưa có bìa'])

<div {{ $attributes->merge(['class' => 'grid place-items-center bg-[color:var(--ui-surface-variant)] text-[color:var(--ui-muted)]']) }}>
    <div class="flex flex-col items-center gap-2 px-2 text-center">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <span class="text-[10px] font-semibold uppercase tracking-[0.02em]">{{ $label }}</span>
    </div>
</div>
