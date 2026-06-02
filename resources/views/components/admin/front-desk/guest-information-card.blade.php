@props([
    'guest' => null,
    'loading' => false,
    'results' => [],
    'selectedGuest' => null,
    'searchError' => null,
])

@php
    $selectedGuestData = is_object($selectedGuest) || is_array($selectedGuest) ? (array) $selectedGuest : [];
@endphp

<section class="kt-card">
    <div class="kt-card-header flex items-center justify-between gap-4">
        <div>
            <h3 class="kt-card-title">Guest Information</h3>
            <div class="text-sm text-secondary-foreground">Search or create a guest profile before walk-in check-in.</div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">Section 1</span>
            <button type="button" class="kt-btn kt-btn-primary kt-btn-sm">Quick Add Guest</button>
        </div>
    </div>

    <div class="kt-card-content p-4 sm:p-5">
        <x-admin.front-desk.guest-search
            :results="$results"
            :selected-guest="$selectedGuest ?? $guest"
            :loading="$loading"
            :error="$searchError"
        />
    </div>
</section>