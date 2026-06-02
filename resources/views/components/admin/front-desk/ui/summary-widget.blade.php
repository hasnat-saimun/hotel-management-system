@props([
    'title' => null,
    'subtitle' => null,
    'items' => [],
    'totalLabel' => 'Grand Total',
    'totalValue' => '0.00',
])

<div class="rounded-2xl border border-border bg-muted/30 p-4">
    @if($title || $subtitle)
        <div>
            @if($title)
                <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">{{ $title }}</div>
            @endif
            @if($subtitle)
                <div class="mt-1 text-sm text-secondary-foreground">{{ $subtitle }}</div>
            @endif
        </div>
    @endif

    <dl class="mt-3 grid gap-3 text-sm">
        @foreach($items as $item)
            <div class="flex items-center justify-between gap-3">
                <dt class="text-secondary-foreground">{{ $item['label'] ?? '-' }}</dt>
                <dd class="font-medium text-foreground">{{ $item['value'] ?? '-' }}</dd>
            </div>
        @endforeach
        <div class="flex items-center justify-between gap-3 border-t border-border pt-3 text-base">
            <dt class="font-semibold text-foreground">{{ $totalLabel }}</dt>
            <dd class="font-semibold text-foreground">{{ $totalValue }}</dd>
        </div>
    </dl>
</div>