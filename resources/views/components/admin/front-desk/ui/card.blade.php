@props([
    'title' => null,
    'subtitle' => null,
    'badge' => null,
    'padding' => 'p-4 sm:p-5',
])

<section {{ $attributes->merge(['class' => 'kt-card']) }}>
    @if($title || $subtitle || $badge)
        <div class="kt-card-header flex items-center justify-between gap-4">
            <div>
                @if($title)
                    <h3 class="kt-card-title">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <div class="text-sm text-secondary-foreground">{{ $subtitle }}</div>
                @endif
            </div>

            @if($badge)
                <span class="kt-badge kt-badge-sm kt-badge-outline">{{ $badge }}</span>
            @endif
        </div>
    @endif

    <div class="kt-card-content {{ $padding }}">
        {{ $slot }}
    </div>
</section>