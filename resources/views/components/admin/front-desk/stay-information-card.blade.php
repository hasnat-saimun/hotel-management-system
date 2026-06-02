@props([
    'errors' => null,
])

@php
    $hasErrors = $errors && method_exists($errors, 'any') ? $errors->any() : false;

    $fieldClass = function (string $name) use ($errors) {
        $base = 'kt-input w-full';

        if ($errors && method_exists($errors, 'has') && $errors->has($name)) {
            return $base . ' border-danger focus:border-danger focus:ring-danger/20';
        }

        return $base;
    };

    $numberFieldClass = function (string $name) use ($errors) {
        $base = 'kt-input w-full text-center';

        if ($errors && method_exists($errors, 'has') && $errors->has($name)) {
            return $base . ' border-danger focus:border-danger focus:ring-danger/20';
        }

        return $base;
    };

    $formRows = [
        [
            ['name' => 'check_in_date', 'label' => 'Check-In Date', 'type' => 'date', 'placeholder' => 'Select arrival date', 'span' => 'sm:col-span-1'],
            ['name' => 'expected_check_out_date', 'label' => 'Expected Check-Out Date', 'type' => 'date', 'placeholder' => 'Select departure date', 'span' => 'sm:col-span-1'],
        ],
        [
            ['name' => 'adults', 'label' => 'Adults', 'type' => 'number', 'placeholder' => '1', 'span' => 'sm:col-span-1'],
            ['name' => 'children', 'label' => 'Children', 'type' => 'number', 'placeholder' => '0', 'span' => 'sm:col-span-1'],
        ],
    ];

    $recommendations = [
        'Keep check-in and expected check-out close together to reduce missed date errors.',
        'Use numeric steppers for adults and children to speed up front-desk entry.',
        'Reserve special requests and internal notes for different audiences to keep the UI clear.',
        'Show inline validation near the field and a compact error summary above the form when needed.',
    ];
@endphp

<section class="kt-card">
    <div class="kt-card-header flex items-center justify-between gap-4">
        <div>
            <h3 class="kt-card-title">Stay Information</h3>
            <div class="text-sm text-secondary-foreground">Define the stay window, occupancy, and internal handling notes.</div>
        </div>

        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-success">Section 2</span>
    </div>

    <div class="kt-card-content p-4 sm:p-5">
        @if($hasErrors)
            <div class="mb-4 rounded-2xl border border-danger/20 bg-danger/10 px-4 py-3 text-sm text-danger">
                <div class="font-semibold">Please review the stay details below.</div>
                <div class="mt-1">One or more fields require attention before proceeding.</div>
            </div>
        @endif

        <div class="grid gap-5 xl:grid-cols-12">
            <div class="xl:col-span-8">
                <div class="kt-card p-4 sm:p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Form Layout</div>
                            <div class="mt-1 text-sm text-secondary-foreground">Professional spacing for fast front-desk entry.</div>
                        </div>
                        <span class="kt-badge kt-badge-sm kt-badge-outline">Responsive</span>
                    </div>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        @foreach($formRows as $row)
                            @foreach($row as $field)
                                <div class="space-y-2 {{ $field['span'] ?? '' }}">
                                    <label class="text-xs font-medium text-secondary-foreground">{{ $field['label'] }}</label>
                                    <input
                                        type="{{ $field['type'] }}"
                                        name="{{ $field['name'] }}"
                                        class="{{ $fieldClass($field['name']) }}"
                                        placeholder="{{ $field['placeholder'] }}"
                                    />

                                    @if($errors && method_exists($errors, 'has') && $errors->has($field['name']))
                                        <div class="text-xs text-danger">{{ $errors->first($field['name']) }}</div>
                                    @else
                                        <div class="text-[11px] text-secondary-foreground/80">{{ $field['label'] }} is required for a complete stay record.</div>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>

                    <div class="mt-5 grid gap-4 lg:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-secondary-foreground">Special Requests</label>
                            <textarea
                                name="special_requests"
                                rows="4"
                                class="{{ $fieldClass('special_requests') }} resize-none"
                                placeholder="Bed preference, early arrival, accessibility needs, late checkout request"
                            ></textarea>
                            <div class="text-[11px] text-secondary-foreground/80">Visible to front desk and operations teams.</div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-medium text-secondary-foreground">Internal Notes</label>
                            <textarea
                                name="internal_notes"
                                rows="4"
                                class="{{ $fieldClass('internal_notes') }} resize-none"
                                placeholder="Internal handling, payment guidance, escalation notes"
                            ></textarea>
                            <div class="text-[11px] text-secondary-foreground/80">Keep operational context separate from guest-facing requests.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-4">
                <div class="kt-card p-4 sm:p-5 h-full">
                    <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">UX Recommendations</div>
                    <div class="mt-3 space-y-3">
                        @foreach($recommendations as $recommendation)
                            <div class="rounded-xl border border-border bg-muted/20 p-3 text-sm text-foreground">
                                {{ $recommendation }}
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 rounded-2xl border border-dashed border-border bg-muted/20 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Validation UI</div>
                        <div class="mt-2 text-sm text-secondary-foreground">
                            Use inline error states, compact helper text, and a summary banner when the form contains invalid dates or occupancy values.
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl border border-dashed border-border bg-muted/20 p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Form Behavior</div>
                        <div class="mt-2 text-sm text-secondary-foreground">
                            Keep inputs full-width on mobile and switch to two-column or three-column layouts on larger screens.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>