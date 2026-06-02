@props([
    'title' => 'Loading',
    'description' => 'Please wait while data is prepared.',
    'variant' => 'page',
    'count' => 4,
])

@php
    $sizes = [
        'page' => 'min-h-[320px]',
        'guest' => 'min-h-[260px]',
        'room' => 'min-h-[320px]',
        'search' => 'min-h-[220px]',
    ];
    $heightClass = $sizes[$variant] ?? $sizes['page'];
@endphp

<div class="rounded-2xl border border-border bg-background p-4 {{ $heightClass }} animate-pulse" aria-busy="true" aria-live="polite">
    <div class="flex items-start justify-between gap-3">
        <div class="space-y-2">
            <div class="h-5 w-40 rounded bg-muted"></div>
            <div class="h-3 w-64 rounded bg-muted"></div>
        </div>
        <div class="h-6 w-24 rounded-full bg-muted"></div>
    </div>

    <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-{{ $count > 3 ? 3 : 2 }}">
        @for($i = 0; $i < $count; $i++)
            <div class="rounded-xl border border-border bg-muted/20 p-3">
                <div class="h-3 w-20 rounded bg-muted"></div>
                <div class="mt-2 h-4 w-full rounded bg-muted"></div>
                <div class="mt-2 h-3 w-3/4 rounded bg-muted"></div>
            </div>
        @endfor
    </div>
</div>