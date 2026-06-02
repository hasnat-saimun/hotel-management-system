@props([
    'tone' => 'default',
    'size' => 'sm',
    'outline' => true,
])

@php
    $toneClasses = [
        'default' => 'kt-badge-outline',
        'info' => 'kt-badge-outline kt-badge-info',
        'success' => 'kt-badge-outline kt-badge-success',
        'warning' => 'kt-badge-outline kt-badge-warning',
        'danger' => 'kt-badge-destructive',
        'muted' => 'border border-border bg-muted/20 text-secondary-foreground',
    ];

    $sizeClass = $size === 'xs' ? 'kt-badge-xs' : 'kt-badge-sm';
    $toneClass = $toneClasses[$tone] ?? $toneClasses['default'];
@endphp

<span {{ $attributes->merge(['class' => trim('kt-badge ' . $sizeClass . ' ' . $toneClass)]) }}>
    {{ $slot }}
</span>