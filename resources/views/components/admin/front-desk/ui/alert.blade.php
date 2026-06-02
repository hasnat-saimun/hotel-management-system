@props([
    'tone' => 'info',
    'title' => null,
    'dismissible' => false,
])

@php
    $toneClasses = [
        'info' => 'border-info/20 bg-info/10 text-foreground',
        'success' => 'border-success/20 bg-success/10 text-foreground',
        'warning' => 'border-warning/20 bg-warning/10 text-foreground',
        'danger' => 'border-danger/20 bg-danger/10 text-foreground',
        'neutral' => 'border-border bg-muted/20 text-foreground',
    ];

    $toneClass = $toneClasses[$tone] ?? $toneClasses['info'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border px-4 py-3 ' . $toneClass]) }} role="alert" aria-live="polite">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            @if($title)
                <div class="font-semibold">{{ $title }}</div>
            @endif
            <div class="mt-1 text-sm">{{ $slot }}</div>
        </div>

        @if($dismissible)
            <button type="button" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" aria-label="Dismiss alert">
                <i class="ki-filled ki-cross"></i>
            </button>
        @endif
    </div>
</div>