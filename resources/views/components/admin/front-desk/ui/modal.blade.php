@props([
    'id' => 'walk-in-modal',
    'title' => null,
    'size' => 'max-w-[720px]',
])

<div class="kt-modal" data-kt-modal="true" id="{{ $id }}" aria-hidden="true">
    <div class="kt-modal-content {{ $size }} top-5 lg:top-[8%]" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}_title">
        <div class="kt-modal-header">
            <h3 class="kt-modal-title" id="{{ $id }}_title">{{ $title }}</h3>
            <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" data-kt-modal-dismiss="true" type="button" aria-label="Close modal">
                <i class="ki-filled ki-cross"></i>
            </button>
        </div>

        <div class="kt-modal-body">
            {{ $slot }}
        </div>
    </div>
</div>