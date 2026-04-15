<div class="kt-modal" data-kt-modal="true" id="quick_add_guest_modal">
    <div class="kt-modal-content max-w-[650px] top-5 lg:top-[10%]">
        <div class="kt-modal-header">
            <h3 class="kt-modal-title">Quick Add Guest</h3>
            <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" data-kt-modal-dismiss="true" type="button">
                <i class="ki-filled ki-cross"></i>
            </button>
        </div>

        <div class="kt-modal-body grid gap-4 px-5 py-5">
            <div id="quick_add_guest_error" class="p-3 bg-danger/10 text-danger rounded" style="display:none;"></div>

            <form id="quick_add_guest_form" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
                <div>
                    <label class="text-sm text-secondary-foreground required-label">First name</label>
                    <input type="text" class="kt-input w-full" name="first_name" required />
                </div>

                <div>
                    <label class="text-sm text-secondary-foreground required-label">Last name</label>
                    <input type="text" class="kt-input w-full" name="last_name" required />
                </div>

                <div>
                    <label class="text-sm text-secondary-foreground">Email</label>
                    <input type="email" class="kt-input w-full" name="email" placeholder="guest@example.com" />
                </div>

                <div>
                    <label class="text-sm text-secondary-foreground">Phone</label>
                    <input type="text" class="kt-input w-full" name="phone" placeholder="Phone number" />
                </div>

                <div class="lg:col-span-2">
                    <label class="text-sm text-secondary-foreground">Address</label>
                    <textarea class="kt-input w-full" name="address" rows="2"></textarea>
                </div>

                <div class="lg:col-span-2 flex gap-2 justify-end">
                    <button class="kt-btn" type="button" data-kt-modal-dismiss="true">Cancel</button>
                    <button class="kt-btn kt-btn-primary" type="submit">Create Guest</button>
                </div>
            </form>
        </div>
    </div>
</div>
