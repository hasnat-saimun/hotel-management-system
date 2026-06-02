<div
    x-show="quickAddWarningOpen"
    x-cloak
    class="rounded-2xl border border-warning/20 bg-warning/10 p-4 text-foreground"
    role="alert"
    aria-live="assertive"
>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Duplicate Guest Detected</div>
            <div class="mt-1 text-sm text-foreground" x-text="quickAddWarningMessage || 'A matching guest already exists.'"></div>
            <div class="mt-1 text-xs text-secondary-foreground" x-show="quickAddDuplicateMatches && quickAddDuplicateMatches.length > 1" x-text="quickAddDuplicateMatches.length + ' matching guests were found.'"></div>
        </div>

        <span class="kt-badge kt-badge-sm kt-badge-outline kt-badge-warning">Warning</span>
    </div>

    <div class="mt-4 rounded-2xl border border-border bg-background p-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                <div class="text-sm font-semibold text-foreground" x-text="quickAddDuplicateGuest?.full_name || 'Guest' "></div>
                <div class="mt-1 text-sm text-secondary-foreground">
                    <span class="block" x-text="quickAddDuplicateGuest?.phone || '—'"></span>
                    <span class="block" x-text="quickAddDuplicateGuest?.email || '—'"></span>
                    <span class="block" x-text="'Guest ID: ' + (quickAddDuplicateGuest?.guest_id || '—')"></span>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <template x-if="quickAddDuplicateGuest?.vip">
                    <span class="kt-badge kt-badge-outline kt-badge-info">VIP</span>
                </template>
                <template x-if="quickAddDuplicateGuest?.blacklisted">
                    <span class="kt-badge kt-badge-destructive">Blacklisted</span>
                </template>
            </div>
        </div>

            <template x-if="quickAddDuplicateMatches && quickAddDuplicateMatches.length > 1">
                <div class="mt-4 border-t border-border pt-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Matching Guests</div>
                    <div class="mt-3 space-y-2">
                        <template x-for="guest in quickAddDuplicateMatches.slice(0, 3)" :key="guest.id">
                            <div class="flex items-center justify-between gap-3 rounded-xl border border-border bg-muted/20 px-3 py-2 text-sm">
                                <div class="min-w-0">
                                    <div class="font-medium text-foreground" x-text="guest.full_name || 'Guest'"></div>
                                    <div class="text-secondary-foreground" x-text="guest.phone || '—'"></div>
                                </div>
                                <span class="kt-badge kt-badge-sm kt-badge-outline">Use Existing</span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

        <div class="mt-4 flex flex-wrap gap-2">
            <button type="button" class="kt-btn kt-btn-primary" @click="useDuplicateGuest()">Use Existing Guest</button>
            <button type="button" class="kt-btn kt-btn-outline" @click="quickAddWarningOpen = false">Edit Details</button>
        </div>

        <div class="mt-4 text-sm text-secondary-foreground">
            Best practice: reuse the existing profile to preserve billing history, stay history, and guest identity.
        </div>
    </div>
</div>