@props([
    'title' => 'Nothing here yet',
    'description' => 'There is no data to show right now.',
    'actionLabel' => null,
])

<div class="rounded-2xl border border-dashed border-border bg-muted/20 px-6 py-10 text-center">
    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-background shadow-sm">
        <i class="ki-filled ki-file text-xl text-secondary-foreground"></i>
    </div>
    <h4 class="mt-4 text-lg font-semibold text-foreground">{{ $title }}</h4>
    <p class="mx-auto mt-2 max-w-md text-sm text-secondary-foreground">{{ $description }}</p>

    @if($actionLabel)
        <div class="mt-5">
            <button type="button" class="kt-btn kt-btn-primary">{{ $actionLabel }}</button>
        </div>
    @endif
</div>