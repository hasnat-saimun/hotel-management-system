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

                <div>
                    <label class="text-sm text-secondary-foreground">Nationality</label>
                    <input type="text" class="kt-input w-full" name="nationality" />
                </div>

                <div>
                    <label class="text-sm text-secondary-foreground">Date of birth</label>
                    <input type="date" class="kt-input w-full" name="date_of_birth" />
                </div>

                <div>
                    <label class="text-sm text-secondary-foreground">Gender</label>
                    <select class="kt-input w-full" name="gender">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm text-secondary-foreground">ID Type</label>
                    <select class="kt-input w-full" name="id_type">
                        <option value="">Select</option>
                        <option value="passport">Passport</option>
                        <option value="driver_license">Driver license</option>
                        <option value="national_id">National ID</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm text-secondary-foreground">ID Number</label>
                    <input type="text" class="kt-input w-full" name="id_number" />
                </div>

                <div>
                    <label class="text-sm text-secondary-foreground">Company</label>
                    <select class="kt-input w-full" name="company_id">
                        <option value="">None</option>
                        @foreach(($companies ?? collect()) as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm text-secondary-foreground">Travel agent</label>
                    <select class="kt-input w-full" name="travel_agent_id">
                        <option value="">None</option>
                        @foreach(($travelAgents ?? collect()) as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm text-secondary-foreground">Loyalty</label>
                    <select class="kt-input w-full" name="loyalty_id">
                        <option value="">None</option>
                        @foreach(($loyalties ?? collect()) as $l)
                            <option value="{{ $l->id }}">{{ $l->level_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-2 flex items-center gap-6">
                    <label class="inline-flex items-center gap-2 text-sm text-secondary-foreground">
                        <input type="checkbox" name="vip" value="1" /> VIP
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-secondary-foreground">
                        <input type="checkbox" id="quick_blacklisted_toggle" name="blacklisted" value="1" /> Blacklisted
                    </label>
                </div>

                <div class="lg:col-span-2" id="quick_blacklist_fields" style="display:none;">
                    <div class="grid gap-3 grid-cols-1 lg:grid-cols-2">
                        <div class="lg:col-span-2">
                            <label class="text-sm text-secondary-foreground required-label">Blacklist reason</label>
                            <textarea class="kt-input w-full" name="blacklist_reason" rows="2"></textarea>
                        </div>
                        <div>
                            <label class="text-sm text-secondary-foreground">Blocked until</label>
                            <input type="date" class="kt-input w-full" name="blacklist_blocked_until" />
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <label class="text-sm text-secondary-foreground">Notes</label>
                    <textarea class="kt-input w-full" name="notes" rows="3"></textarea>
                </div>

                <div class="lg:col-span-2 flex gap-2 justify-end">
                    <button class="kt-btn" type="button" data-kt-modal-dismiss="true">Cancel</button>
                    <button class="kt-btn kt-btn-primary" type="submit">Create Guest</button>
                </div>
            </form>
        </div>
    </div>
</div>
