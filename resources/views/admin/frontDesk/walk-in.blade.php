@extends('admin.layouts.app')

@section('content')
    <div class="kt-container-fixed">
        <div class="flex flex-col gap-5">
            <div class="flex flex-col gap-4 rounded-2xl border border-border bg-background/80 p-5 shadow-sm backdrop-blur sm:p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-3">
                        <nav aria-label="Breadcrumb" class="text-sm text-secondary-foreground">
                            <ol class="flex flex-wrap items-center gap-2">
                                <li>
                                    <a href="#" class="transition-colors hover:text-foreground">Front Desk</a>
                                </li>
                                <li aria-hidden="true" class="text-border">/</li>
                                <li>
                                    <span class="text-foreground">Walk-In</span>
                                </li>
                            </ol>
                        </nav>

                        <div class="space-y-1">
                            <h1 class="text-2xl font-semibold tracking-tight text-foreground sm:text-3xl">Walk-In</h1>
                            <p class="max-w-3xl text-sm text-secondary-foreground sm:text-base">
                                Front-desk layout for immediate guest intake, room selection, and booking summary.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <span class="kt-badge kt-badge-outline kt-badge-info">Front Desk</span>
                        <span class="kt-badge kt-badge-outline kt-badge-success">Walk-In Ready</span>
                        <span class="kt-badge kt-badge-outline">Responsive Layout</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-border pt-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" class="kt-btn kt-btn-outline">Search Guest</button>
                        <button type="button" class="kt-btn kt-btn-outline">Add Guest</button>
                        <button type="button" class="kt-btn kt-btn-outline">Check Availability</button>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" class="kt-btn kt-btn-outline">Save Draft</button>
                        <button type="button" class="kt-btn kt-btn-primary">Create Walk-In</button>
                    </div>
                </div>
            </div>

            <div class="grid gap-5 xl:grid-cols-12">
                <div class="flex flex-col gap-5 xl:col-span-8">
                    <x-admin.front-desk.guest-information-card />

                    <section class="kt-card">
                        <div class="kt-card-header flex items-center justify-between gap-4">
                            <div>
                                <h3 class="kt-card-title">Stay Information</h3>
                                <div class="text-sm text-secondary-foreground">Define dates, guests, and stay expectations.</div>
                            </div>
                            <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-success">Section 2</span>
                        </div>
                        <div class="kt-card-content p-4 sm:p-5">
                            <div class="grid gap-4 lg:grid-cols-3">
                                <div class="kt-card p-4">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Dates</div>
                                    <div class="mt-4 space-y-3">
                                        <div class="space-y-2">
                                            <div class="text-xs text-secondary-foreground">Check-in Date</div>
                                            <div class="rounded-xl border border-dashed border-border bg-muted/40 p-3 text-sm text-secondary-foreground">Arrival date</div>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="text-xs text-secondary-foreground">Check-out Date</div>
                                            <div class="rounded-xl border border-dashed border-border bg-muted/40 p-3 text-sm text-secondary-foreground">Departure date</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="kt-card p-4">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Occupancy</div>
                                    <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                                        <div class="space-y-2">
                                            <div class="text-xs text-secondary-foreground">Adults</div>
                                            <div class="rounded-xl border border-dashed border-border bg-muted/40 p-3 text-sm text-secondary-foreground">1</div>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="text-xs text-secondary-foreground">Children</div>
                                            <div class="rounded-xl border border-dashed border-border bg-muted/40 p-3 text-sm text-secondary-foreground">0</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="kt-card p-4">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Preferences</div>
                                    <div class="mt-4 space-y-3">
                                        <div class="space-y-2">
                                            <div class="text-xs text-secondary-foreground">Source</div>
                                            <div class="rounded-xl border border-dashed border-border bg-muted/40 p-3 text-sm text-secondary-foreground">Walk-In / Direct</div>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="text-xs text-secondary-foreground">Notes</div>
                                            <div class="rounded-xl border border-dashed border-border bg-muted/40 p-3 text-sm text-secondary-foreground">Special requests or comments</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="kt-card">
                        <div class="kt-card-header flex items-center justify-between gap-4">
                            <div>
                                <h3 class="kt-card-title">Room Selection</h3>
                                <div class="text-sm text-secondary-foreground">Choose room type, availability, and assignment.</div>
                            </div>
                            <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-warning">Section 3</span>
                        </div>
                        <div class="kt-card-content p-4 sm:p-5">
                            <div class="grid gap-4 xl:grid-cols-12">
                                <div class="xl:col-span-4">
                                    <div class="kt-card p-4 h-full">
                                        <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Filters</div>
                                        <div class="mt-4 space-y-3">
                                            <div class="rounded-xl border border-dashed border-border bg-muted/40 p-3 text-sm text-secondary-foreground">Room type filter</div>
                                            <div class="rounded-xl border border-dashed border-border bg-muted/40 p-3 text-sm text-secondary-foreground">Floor filter</div>
                                            <div class="rounded-xl border border-dashed border-border bg-muted/40 p-3 text-sm text-secondary-foreground">Occupancy / status filter</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="xl:col-span-8">
                                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                                        <div class="kt-card p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <div class="text-sm font-semibold text-foreground">Standard Queen</div>
                                                    <div class="text-xs text-secondary-foreground">Room type</div>
                                                </div>
                                                <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-success">Available</span>
                                            </div>
                                            <div class="mt-4 rounded-xl border border-dashed border-border bg-muted/40 p-3 text-sm text-secondary-foreground">Room card placeholder</div>
                                        </div>
                                        <div class="kt-card p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <div class="text-sm font-semibold text-foreground">Deluxe Twin</div>
                                                    <div class="text-xs text-secondary-foreground">Room type</div>
                                                </div>
                                                <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-success">Available</span>
                                            </div>
                                            <div class="mt-4 rounded-xl border border-dashed border-border bg-muted/40 p-3 text-sm text-secondary-foreground">Room card placeholder</div>
                                        </div>
                                        <div class="kt-card p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <div class="text-sm font-semibold text-foreground">Family Suite</div>
                                                    <div class="text-xs text-secondary-foreground">Room type</div>
                                                </div>
                                                <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-warning">Limited</span>
                                            </div>
                                            <div class="mt-4 rounded-xl border border-dashed border-border bg-muted/40 p-3 text-sm text-secondary-foreground">Room card placeholder</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="xl:col-span-4">
                    <div class="xl:sticky xl:top-6">
                        <section class="kt-card h-full">
                            <div class="kt-card-header flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="kt-card-title">Booking Summary</h3>
                                    <div class="text-sm text-secondary-foreground">Review the final walk-in snapshot.</div>
                                </div>
                                <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-info">Section 4</span>
                            </div>
                            <div class="kt-card-content p-4 sm:p-5">
                                <div class="space-y-4">
                                    <div class="rounded-2xl border border-border bg-muted/30 p-4">
                                        <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Guest</div>
                                        <div class="mt-2 text-base font-semibold text-foreground">Guest Name</div>
                                        <div class="mt-1 text-sm text-secondary-foreground">email@example.com</div>
                                    </div>

                                    <div class="rounded-2xl border border-border bg-muted/30 p-4">
                                        <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Stay</div>
                                        <dl class="mt-3 grid gap-3 text-sm">
                                            <div class="flex items-center justify-between gap-3">
                                                <dt class="text-secondary-foreground">Dates</dt>
                                                <dd class="font-medium text-foreground">Arrival - Departure</dd>
                                            </div>
                                            <div class="flex items-center justify-between gap-3">
                                                <dt class="text-secondary-foreground">Occupancy</dt>
                                                <dd class="font-medium text-foreground">1 adult • 0 children</dd>
                                            </div>
                                            <div class="flex items-center justify-between gap-3">
                                                <dt class="text-secondary-foreground">Room</dt>
                                                <dd class="font-medium text-foreground">Selected room</dd>
                                            </div>
                                        </dl>
                                    </div>

                                    <div class="rounded-2xl border border-border bg-muted/30 p-4">
                                        <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Pricing</div>
                                        <dl class="mt-3 grid gap-3 text-sm">
                                            <div class="flex items-center justify-between gap-3">
                                                <dt class="text-secondary-foreground">Room rate</dt>
                                                <dd class="font-medium text-foreground">0.00</dd>
                                            </div>
                                            <div class="flex items-center justify-between gap-3">
                                                <dt class="text-secondary-foreground">Taxes</dt>
                                                <dd class="font-medium text-foreground">0.00</dd>
                                            </div>
                                            <div class="flex items-center justify-between gap-3 border-t border-border pt-3 text-base">
                                                <dt class="font-semibold text-foreground">Total</dt>
                                                <dd class="font-semibold text-foreground">0.00</dd>
                                            </div>
                                        </dl>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" class="kt-btn kt-btn-outline w-full sm:w-auto">Clear</button>
                                        <button type="button" class="kt-btn kt-btn-primary w-full sm:w-auto">Confirm Walk-In</button>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection
