@props([
    'results' => [],
    'selectedGuest' => null,
    'loading' => false,
    'error' => null,
])

@php
    $selectedData = is_object($selectedGuest) || is_array($selectedGuest) ? (array) $selectedGuest : [];

    $searchRows = [
        ['name' => 'guest_name', 'label' => 'Name', 'placeholder' => 'Search by guest name'],
        ['name' => 'guest_phone', 'label' => 'Phone', 'placeholder' => 'Search by phone number'],
        ['name' => 'guest_email', 'label' => 'Email', 'placeholder' => 'Search by email address'],
    ];

    $guestResults = collect($results)->map(function ($guest) {
        $guest = (array) $guest;

        return [
            'id' => $guest['id'] ?? null,
            'full_name' => $guest['full_name'] ?? trim(($guest['first_name'] ?? '') . ' ' . ($guest['last_name'] ?? '')),
            'phone' => $guest['phone'] ?? '-',
            'email' => $guest['email'] ?? '-',
            'last_stay_date' => $guest['last_stay_date'] ?? null,
            'vip' => (bool) ($guest['vip'] ?? false),
            'blacklisted' => (bool) ($guest['blacklisted'] ?? false),
        ];
    })->values();
@endphp

<section
    class="kt-card"
    x-data="{
        open: true,
        loading: {{ $loading ? 'true' : 'false' }},
        error: @js($error),
        selectedGuest: @js($selectedData),
        quickAddOpen: false,
        quickAddSaving: false,
        quickAddError: null,
        quickAddWarningOpen: false,
        quickAddWarningMessage: null,
        quickAddDuplicateGuest: null,
        quickAddDuplicateMatches: [],
        quickAddRoute: @js(route('admin.api.guests.quick-add')),
        quickAddForm: {
            full_name: '',
            phone: '',
            email: '',
            address: '',
            nationality: '',
            id_number: '',
        },
        query: {
            name: '',
            phone: '',
            email: '',
        },
        results: @js($guestResults),
        hasQuery() {
            return this.query.name.trim() !== '' || this.query.phone.trim() !== '' || this.query.email.trim() !== '';
        },
        hasResults() {
            return Array.isArray(this.results) && this.results.length > 0;
        },
        resetQuickAddState() {
            this.quickAddError = null;
            this.quickAddWarningOpen = false;
            this.quickAddWarningMessage = null;
            this.quickAddDuplicateGuest = null;
            this.quickAddDuplicateMatches = [];
        },
        clearSearch() {
            this.query.name = '';
            this.query.phone = '';
            this.query.email = '';
            this.open = true;
        },
        openQuickAdd() {
            this.resetQuickAddState();
            this.quickAddOpen = true;
        },
        useGuest(guest) {
            this.selectedGuest = guest;
            this.open = false;
            this.quickAddOpen = false;
            this.quickAddWarningOpen = false;
        },
        useDuplicateGuest() {
            if (!this.quickAddDuplicateGuest) {
                return;
            }

            this.useGuest(this.quickAddDuplicateGuest);
        },
        openSelectedGuest(guest) {
            this.useGuest(guest);
        },
        openResults() {
            this.open = true;
        },
        dismissError() {
            this.error = null;
        },
        async submitQuickAddGuest() {
            this.quickAddSaving = true;
            this.quickAddError = null;
            this.quickAddWarningOpen = false;
            this.quickAddWarningMessage = null;
            this.quickAddDuplicateGuest = null;
            this.quickAddDuplicateMatches = [];

            try {
                const response = await fetch(this.quickAddRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    },
                    body: JSON.stringify(this.quickAddForm),
                });

                const payload = await response.json();

                if (response.status === 409 && payload?.duplicate_found) {
                    this.quickAddWarningOpen = true;
                    this.quickAddWarningMessage = payload?.message || 'A matching guest already exists.';
                    this.quickAddDuplicateGuest = payload?.primary_guest || payload?.matches?.[0] || null;
                    this.quickAddDuplicateMatches = Array.isArray(payload?.matches) ? payload.matches : [];
                    return;
                }

                if (!response.ok || !payload.success) {
                    const message = payload?.message || 'Unable to create guest.';
                    const validationMessage = payload?.errors ? Object.values(payload.errors).flat().join(' ') : '';
                    throw new Error(validationMessage || message);
                }

                this.selectedGuest = payload.guest;
                this.quickAddOpen = false;
                this.open = false;
                this.quickAddForm = {
                    full_name: '',
                    phone: '',
                    email: '',
                    address: '',
                    nationality: '',
                    id_number: '',
                };
            } catch (error) {
                this.quickAddError = error.message || 'Unable to create guest.';
            } finally {
                this.quickAddSaving = false;
            }
        }
    }"
    @keydown.escape.window="open = false"
>
    <div class="kt-card-header flex items-center justify-between gap-4">
        <div>
            <h3 class="kt-card-title">Guest Search</h3>
            <div class="text-sm text-secondary-foreground">Search existing guests by name, phone, or email before adding a new profile.</div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">Alpine.js</span>
            <button type="button" class="kt-btn kt-btn-primary kt-btn-sm" @click="openQuickAdd()">Quick Add Guest</button>
        </div>
    </div>

    <div class="kt-card-content p-4 sm:p-5">
        <div class="grid gap-5 xl:grid-cols-12">
            <div class="xl:col-span-5">
                <div class="kt-card p-4 h-full">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Search Input</div>
                            <div class="mt-1 text-sm text-secondary-foreground">Use one or more fields to find a guest quickly.</div>
                        </div>
                        <span class="kt-badge kt-badge-sm kt-badge-outline">Query</span>
                    </div>

                    <div class="mt-4 grid gap-4">
                        @foreach($searchRows as $row)
                            <div class="space-y-2">
                                <label for="{{ $row['name'] }}" class="text-xs font-medium text-secondary-foreground">{{ $row['label'] }}</label>
                                <div class="relative">
                                    <input
                                        id="{{ $row['name'] }}"
                                        type="text"
                                        class="kt-input w-full pr-10"
                                        placeholder="{{ $row['placeholder'] }}"
                                        x-model="query.{{ $row['name'] === 'guest_name' ? 'name' : ($row['name'] === 'guest_phone' ? 'phone' : 'email') }}"
                                        @focus="openResults()"
                                        @input="openResults()"
                                    />
                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-secondary-foreground">
                                        <i class="ki-filled ki-magnifier text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <button type="button" class="kt-btn kt-btn-outline" @click="openResults()">Search Guest</button>
                        <button type="button" class="kt-btn kt-btn-outline" @click="clearSearch()">Clear</button>
                        <button type="button" class="kt-btn kt-btn-primary" @click="openQuickAdd()">Quick Add Guest</button>
                    </div>

                    <div class="mt-4 rounded-2xl border border-dashed border-border bg-muted/20 p-4 text-sm text-secondary-foreground">
                        UX best practice: search across one field or combine all three, and keep exact-match selection one click away.
                    </div>
                </div>
            </div>

            <div class="xl:col-span-7">
                <div class="relative h-full">
                    <div class="space-y-3 rounded-2xl border border-border bg-background p-4 sm:p-5 min-h-[360px]">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Results Dropdown</div>
                                <div class="mt-1 text-sm text-secondary-foreground">Choose a guest to prefill the Walk-In form.</div>
                            </div>
                            <span class="kt-badge kt-badge-sm kt-badge-outline">Reusable</span>
                        </div>

                        <template x-if="loading">
                            <div class="rounded-2xl border border-border bg-muted/20 p-4 animate-pulse">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="space-y-2">
                                        <div class="h-4 w-36 rounded bg-muted"></div>
                                        <div class="h-3 w-48 rounded bg-muted"></div>
                                    </div>
                                    <div class="h-6 w-24 rounded-full bg-muted"></div>
                                </div>
                                <div class="mt-4 space-y-3">
                                    <div class="h-3 w-full rounded bg-muted"></div>
                                    <div class="h-3 w-4/5 rounded bg-muted"></div>
                                    <div class="h-3 w-3/5 rounded bg-muted"></div>
                                </div>
                            </div>
                        </template>

                        <template x-if="!loading && error">
                            <div class="rounded-2xl border border-danger/20 bg-danger/10 p-4 text-danger" role="alert" aria-live="assertive">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="font-semibold">Search error</div>
                                        <div class="mt-1 text-sm" x-text="error"></div>
                                    </div>
                                    <button type="button" class="kt-btn kt-btn-sm kt-btn-outline" @click="dismissError()">Dismiss</button>
                                </div>
                            </div>
                        </template>

                        <template x-if="!loading && !error && selectedGuest && selectedGuest.id">
                            <div class="rounded-2xl border border-success/20 bg-success/10 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Selected Guest</div>
                                        <div class="mt-1 text-lg font-semibold text-foreground" x-text="selectedGuest.full_name || selectedGuest.name || 'Guest' "></div>
                                        <div class="mt-1 text-sm text-secondary-foreground" x-text="selectedGuest.email || '—'"></div>
                                    </div>
                                    <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-success">Selected</span>
                                </div>
                            </div>
                        </template>

                        <template x-if="!loading && !error && !selectedGuest.id && !hasResults()">
                            <div class="rounded-2xl border border-dashed border-border bg-muted/20 px-6 py-10 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-background shadow-sm">
                                    <i class="ki-filled ki-user text-xl text-secondary-foreground"></i>
                                </div>
                                <h4 class="mt-4 text-lg font-semibold text-foreground">Empty Results</h4>
                                <p class="mx-auto mt-2 max-w-md text-sm text-secondary-foreground">
                                    Start typing a name, phone number, or email address to show matching guests here.
                                </p>
                                <div class="mt-5">
                                    <button type="button" class="kt-btn kt-btn-primary" @click="openQuickAdd()">Quick Add Guest</button>
                                </div>
                            </div>
                        </template>

                        <template x-if="!loading && !error && !selectedGuest.id && hasResults()">
                            <div class="space-y-3" role="listbox" aria-label="Guest search results">
                                @foreach($guestResults as $result)
                                    <button
                                        type="button"
                                        class="w-full rounded-2xl border border-border bg-muted/20 p-4 text-left transition-all hover:-translate-y-0.5 hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/50"
                                        @click="useGuest(@js($result))"
                                        role="option"
                                    >
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <div class="text-base font-semibold text-foreground break-words">{{ $result['full_name'] }}</div>
                                                    @if($result['vip'])
                                                        <x-admin.front-desk.ui.badge tone="info">VIP</x-admin.front-desk.ui.badge>
                                                    @endif
                                                    @if($result['blacklisted'])
                                                        <x-admin.front-desk.ui.badge tone="danger">Blacklisted</x-admin.front-desk.ui.badge>
                                                    @endif
                                                </div>
                                                <div class="mt-1 flex flex-wrap gap-3 text-sm text-secondary-foreground">
                                                    <span>{{ $result['phone'] }}</span>
                                                    <span>{{ $result['email'] }}</span>
                                                    <span>Last Stay: {{ $result['last_stay_date'] ?? '—' }}</span>
                                                </div>
                                            </div>

                                            <span class="kt-badge kt-badge-sm kt-badge-outline">Select</span>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <x-admin.front-desk.quick-add-guest-modal route="{{ route('admin.api.guests.quick-add') }}" />
    </div>
</section>