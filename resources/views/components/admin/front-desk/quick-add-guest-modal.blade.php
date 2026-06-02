@props([
    'route' => '#',
])

<div
    x-cloak
    x-show="quickAddOpen"
    class="fixed inset-0 z-[60] flex items-center justify-center p-4"
    aria-modal="true"
    role="dialog"
    aria-labelledby="quick_add_guest_modal_title"
    @keydown.escape.window="quickAddOpen = false"
>
    <div class="absolute inset-0 bg-foreground/50 backdrop-blur-sm" @click="quickAddOpen = false"></div>

    <div class="relative z-[61] w-full max-w-[760px] overflow-hidden rounded-2xl border border-border bg-background shadow-2xl">
        <div class="flex items-center justify-between gap-4 border-b border-border px-5 py-4 sm:px-6">
            <div>
                <h3 id="quick_add_guest_modal_title" class="text-lg font-semibold text-foreground">Quick Add Guest</h3>
                <p class="text-sm text-secondary-foreground">Create a guest and auto-select them for the walk-in flow.</p>
            </div>

            <button type="button" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" @click="quickAddOpen = false" aria-label="Close modal">
                <i class="ki-filled ki-cross"></i>
            </button>
        </div>

        <div class="max-h-[80vh] overflow-y-auto px-5 py-5 sm:px-6">
            <div class="space-y-4">
                <template x-if="quickAddError">
                    <div class="rounded-2xl border border-danger/20 bg-danger/10 px-4 py-3 text-sm text-danger" role="alert" aria-live="assertive" x-text="quickAddError"></div>
                </template>

                <div class="rounded-2xl border border-border bg-muted/20 p-4">
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div class="space-y-2 lg:col-span-2">
                            <label for="quick_add_full_name" class="text-xs font-medium text-secondary-foreground required-label">Full Name</label>
                            <input id="quick_add_full_name" type="text" class="kt-input w-full" x-model="quickAddForm.full_name" placeholder="Guest full name" />
                        </div>

                        <div class="space-y-2">
                            <label for="quick_add_phone" class="text-xs font-medium text-secondary-foreground required-label">Phone Number</label>
                            <input id="quick_add_phone" type="text" class="kt-input w-full" x-model="quickAddForm.phone" placeholder="Phone number" />
                        </div>

                        <div class="space-y-2">
                            <label for="quick_add_email" class="text-xs font-medium text-secondary-foreground">Email</label>
                            <input id="quick_add_email" type="email" class="kt-input w-full" x-model="quickAddForm.email" placeholder="guest@example.com" />
                        </div>

                        <div class="space-y-2 lg:col-span-2">
                            <label for="quick_add_address" class="text-xs font-medium text-secondary-foreground">Address</label>
                            <textarea id="quick_add_address" class="kt-input w-full resize-none" rows="2" x-model="quickAddForm.address" placeholder="Guest address"></textarea>
                        </div>

                        <div class="space-y-2">
                            <label for="quick_add_nationality" class="text-xs font-medium text-secondary-foreground">Nationality</label>
                            <input id="quick_add_nationality" type="text" class="kt-input w-full" x-model="quickAddForm.nationality" placeholder="Nationality" />
                        </div>

                        <div class="space-y-2">
                            <label for="quick_add_id_number" class="text-xs font-medium text-secondary-foreground">ID / Passport Number</label>
                            <input id="quick_add_id_number" type="text" class="kt-input w-full" x-model="quickAddForm.id_number" placeholder="ID or passport number" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-dashed border-border bg-background p-4">
                    <div class="space-y-1">
                        <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Validation Strategy</div>
                        <div class="text-sm text-secondary-foreground">Full name and phone are required. Email is optional and validated when present.</div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="button" class="kt-btn kt-btn-outline" @click="quickAddOpen = false">Cancel</button>
                        <button type="button" class="kt-btn kt-btn-primary" :disabled="quickAddSaving" @click="submitQuickAddGuest()">
                            <span x-show="!quickAddSaving">Create Guest</span>
                            <span x-show="quickAddSaving">Saving...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>