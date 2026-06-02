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

                    <x-admin.front-desk.stay-information-card :errors="$errors ?? null" />

                    <x-admin.front-desk.room-selection-card />
                </div>

                <x-admin.front-desk.booking-summary-sidebar />
            </div>
        </div>
    </div>
@endsection
