@props([
    'name' => 'q',
    'label' => 'Search',
    'placeholder' => 'Type to search',
    'value' => null,
    'actionLabel' => 'Search',
])

<div class="grid gap-2">
    <label for="{{ $name }}" class="text-xs font-medium text-secondary-foreground">{{ $label }}</label>
    <div class="flex flex-col gap-2 sm:flex-row">
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="text"
            class="kt-input w-full"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
        />
        <button type="button" class="kt-btn kt-btn-primary sm:w-auto">{{ $actionLabel }}</button>
    </div>
</div>