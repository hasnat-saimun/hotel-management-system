@props([
    'guest' => null,
    'loading' => false,
])

@php
    $isObject = is_object($guest) || is_array($guest);
    $guestData = $isObject ? (array) $guest : [];

    $firstName = trim((string) ($guestData['first_name'] ?? ''));
    $lastName = trim((string) ($guestData['last_name'] ?? ''));
    $fullName = trim($firstName . ' ' . $lastName);
    $fullName = $fullName !== '' ? $fullName : 'Guest profile';

    $guestId = $guestData['id'] ?? null;
    $email = $guestData['email'] ?? null;
    $phone = $guestData['phone'] ?? null;
    $nationality = $guestData['nationality'] ?? null;
    $address = $guestData['address'] ?? null;
    $dateOfBirth = $guestData['date_of_birth'] ?? null;
    $gender = $guestData['gender'] ?? null;
    $idType = $guestData['id_type'] ?? null;
    $idNumber = $guestData['id_number'] ?? null;
    $companyId = $guestData['company_id'] ?? null;
    $travelAgentId = $guestData['travel_agent_id'] ?? null;
    $loyaltyId = $guestData['loyalty_id'] ?? null;
    $notes = $guestData['notes'] ?? null;

    $vip = (bool) ($guestData['vip'] ?? false);
    $blacklisted = (bool) ($guestData['blacklisted'] ?? false);

    $reservationsCount = $guestData['reservations_count'] ?? null;
    $returningGuest = ($reservationsCount !== null && (int) $reservationsCount > 0) || ($guestId !== null && $guestId !== '');

    $summaryItems = [
        ['label' => 'First Name', 'value' => $firstName !== '' ? $firstName : '-'],
        ['label' => 'Last Name', 'value' => $lastName !== '' ? $lastName : '-'],
        ['label' => 'Email', 'value' => $email ?: '-'],
        ['label' => 'Phone', 'value' => $phone ?: '-'],
        ['label' => 'Nationality', 'value' => $nationality ?: '-'],
        ['label' => 'Date of Birth', 'value' => $dateOfBirth ?: '-'],
        ['label' => 'Gender', 'value' => $gender ? ucfirst((string) $gender) : '-'],
        ['label' => 'ID Type', 'value' => $idType ? ucwords(str_replace('_', ' ', (string) $idType)) : '-'],
        ['label' => 'ID Number', 'value' => $idNumber ?: '-'],
        ['label' => 'Company ID', 'value' => $companyId ?: '-'],
        ['label' => 'Travel Agent ID', 'value' => $travelAgentId ?: '-'],
        ['label' => 'Loyalty ID', 'value' => $loyaltyId ?: '-'],
        ['label' => 'Address', 'value' => $address ?: '-'],
        ['label' => 'Notes', 'value' => $notes ?: '-'],
    ];

    $searchFields = [
        ['label' => 'Name', 'placeholder' => 'Search by guest name'],
        ['label' => 'Phone', 'placeholder' => 'Search by phone number'],
        ['label' => 'Email', 'placeholder' => 'Search by email address'],
    ];
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
        <div class="grid gap-5 xl:grid-cols-12">
            <div class="xl:col-span-5">
                <div class="kt-card p-4 h-full">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Guest Search</div>
                            <div class="mt-1 text-sm text-secondary-foreground">Find an existing guest by identity or contact.</div>
                        </div>
                        <span class="kt-badge kt-badge-sm kt-badge-outline">Search</span>
                    </div>

                    <div class="mt-4 grid gap-4">
                        @foreach($searchFields as $field)
                            <div class="space-y-2">
                                <label class="text-xs text-secondary-foreground">{{ $field['label'] }}</label>
                                <input type="text" class="kt-input w-full" placeholder="{{ $field['placeholder'] }}" />
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <button type="button" class="kt-btn kt-btn-outline">Search Guest</button>
                        <button type="button" class="kt-btn kt-btn-outline">Clear</button>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-7">
                @if($loading)
                    <div class="kt-card p-4 h-full animate-pulse">
                        <div class="flex items-start justify-between gap-3">
                            <div class="space-y-2">
                                <div class="h-5 w-36 rounded bg-muted"></div>
                                <div class="h-3 w-48 rounded bg-muted"></div>
                            </div>
                            <div class="h-6 w-24 rounded-full bg-muted"></div>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            @for($i = 0; $i < 8; $i++)
                                <div class="rounded-xl border border-border bg-muted/30 p-3">
                                    <div class="h-3 w-20 rounded bg-muted"></div>
                                    <div class="mt-2 h-4 w-full rounded bg-muted"></div>
                                </div>
                            @endfor
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-3">
                            <div class="h-10 rounded-xl bg-muted"></div>
                            <div class="h-10 rounded-xl bg-muted"></div>
                            <div class="h-10 rounded-xl bg-muted"></div>
                        </div>
                    </div>
                @elseif(!$isObject || $fullName === 'Guest profile' || ($email === null && $phone === null && $guestId === null))
                    <div class="kt-card p-4 h-full">
                        <div class="flex h-full min-h-[360px] flex-col items-center justify-center rounded-2xl border border-dashed border-border bg-muted/20 px-6 py-10 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-background shadow-sm">
                                <i class="ki-filled ki-user text-xl text-secondary-foreground"></i>
                            </div>
                            <div class="mt-4 space-y-2">
                                <h4 class="text-lg font-semibold text-foreground">No guest selected</h4>
                                <p class="max-w-md text-sm text-secondary-foreground">
                                    Search by name, phone, or email, or add a new guest profile before continuing the walk-in.
                                </p>
                            </div>
                            <div class="mt-5 flex flex-wrap justify-center gap-2">
                                <button type="button" class="kt-btn kt-btn-primary">Quick Add Guest</button>
                                <button type="button" class="kt-btn kt-btn-outline">Open Guest Search</button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="kt-card p-4 h-full">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h4 class="text-xl font-semibold tracking-tight text-foreground break-words">{{ $fullName }}</h4>
                                    @if($vip)
                                        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">VIP</span>
                                    @endif
                                    @if($returningGuest)
                                        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-success">Returning Guest</span>
                                    @endif
                                    @if($blacklisted)
                                        <span class="kt-badge kt-badge-sm kt-badge-destructive">Blacklisted</span>
                                    @endif
                                </div>
                                <div class="mt-1 text-sm text-secondary-foreground">Guest ID: <span class="font-medium text-foreground">{{ $guestId ?? '-' }}</span></div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <span class="kt-badge kt-badge-outline kt-badge-info">Profile Loaded</span>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach($summaryItems as $item)
                                <div class="rounded-xl border border-border bg-muted/20 p-3">
                                    <div class="text-[11px] uppercase tracking-wide text-secondary-foreground">{{ $item['label'] }}</div>
                                    <div class="mt-1 text-sm font-medium text-foreground break-words">{{ $item['value'] }}</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-5 rounded-2xl border border-border bg-background p-4">
                            <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Additional Indicators</div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @if($vip)
                                    <span class="kt-badge kt-badge-outline kt-badge-info">VIP Guest</span>
                                @endif
                                @if($returningGuest)
                                    <span class="kt-badge kt-badge-outline kt-badge-success">Returning Guest</span>
                                @endif
                                @if($blacklisted)
                                    <span class="kt-badge kt-badge-destructive">Blacklist Warning</span>
                                @endif
                                @if(! $vip && ! $returningGuest && ! $blacklisted)
                                    <span class="kt-badge kt-badge-outline">No flags</span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-2">
                            <button type="button" class="kt-btn kt-btn-outline">Edit Guest</button>
                            <button type="button" class="kt-btn kt-btn-outline">View Full Profile</button>
                            <button type="button" class="kt-btn kt-btn-primary">Use Guest</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>