@props([
    'title' => 'Success',
    'description' => 'The action completed successfully.',
    'actionLabel' => null,
])

<div class="rounded-2xl border border-success/20 bg-success/10 px-4 py-3 text-foreground" role="status" aria-live="polite">
    <div class="flex items-start gap-3">
        <div class="mt-0.5 flex h-8 w-8 items-center justify-center rounded-full bg-success/20 text-success">
            <i class="ki-filled ki-check text-sm"></i>
        </div>

        <div class="min-w-0 flex-1">
            <div class="font-semibold text-success">{{ $title }}</div>
            <div class="mt-1 text-sm text-secondary-foreground">{{ $description }}</div>

            @if($actionLabel)
                <div class="mt-3">
                    <button type="button" class="kt-btn kt-btn-outline kt-btn-sm">{{ $actionLabel }}</button>
                </div>
            @endif
        </div>
    </div>
</div>