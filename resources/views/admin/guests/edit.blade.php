@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Edit Guest</h3>
            <div class="text-sm text-secondary-foreground">Update guest profile</div>
        </div>
        <a class="kt-btn" href="{{ route('admin.guests.index') }}">Back</a>
    </div>

    <div class="kt-card-content p-4">
        @if($errors->any())
            <div class="mb-4 p-3 bg-danger/10 text-danger rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.guests.update', $guest->id) }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            @method('PUT')

            <div>
                <label class="text-sm text-secondary-foreground required-label">First name</label>
                <input type="text" class="kt-input w-full" name="first_name" value="{{ old('first_name', $guest->first_name) }}" required />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground required-label">Last name</label>
                <input type="text" class="kt-input w-full" name="last_name" value="{{ old('last_name', $guest->last_name) }}" required />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Email</label>
                <input type="email" class="kt-input w-full" name="email" value="{{ old('email', $guest->email) }}" placeholder="guest@example.com" />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Phone</label>
                <input type="text" class="kt-input w-full" name="phone" value="{{ old('phone', $guest->phone) }}" placeholder="Phone number" />
            </div>

            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Address</label>
                <textarea class="kt-input w-full" name="address" rows="2">{{ old('address', $guest->address) }}</textarea>
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Nationality</label>
                <input type="text" class="kt-input w-full" name="nationality" value="{{ old('nationality', $guest->nationality) }}" />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Date of birth</label>
                <input type="date" class="kt-input w-full" name="date_of_birth" value="{{ old('date_of_birth', optional($guest->date_of_birth)->format('Y-m-d')) }}" />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Gender</label>
                @php($gender = old('gender', $guest->gender))
                <select class="kt-input w-full" name="gender">
                    <option value="">Select</option>
                    <option value="male" @selected($gender==='male')>Male</option>
                    <option value="female" @selected($gender==='female')>Female</option>
                    <option value="other" @selected($gender==='other')>Other</option>
                </select>
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Company</label>
                <select class="kt-input w-full" name="company_id">
                    <option value="">None</option>
                    @php($companyId = old('company_id', $guest->company_id))
                    @foreach($companies as $c)
                        <option value="{{ $c->id }}" @selected((string)$companyId === (string)$c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Travel agent</label>
                <select class="kt-input w-full" name="travel_agent_id">
                    <option value="">None</option>
                    @php($agentId = old('travel_agent_id', $guest->travel_agent_id))
                    @foreach($travelAgents as $a)
                        <option value="{{ $a->id }}" @selected((string)$agentId === (string)$a->id)>{{ $a->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Loyalty</label>
                <select class="kt-input w-full" name="loyalty_id">
                    <option value="">None</option>
                    @php($loyaltyId = old('loyalty_id', $guest->loyalty_id))
                    @foreach($loyalties as $l)
                        <option value="{{ $l->id }}" @selected((string)$loyaltyId === (string)$l->id)>{{ $l->level_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2 flex items-center gap-6">
                <label class="inline-flex items-center gap-2 text-sm text-secondary-foreground">
                    <input type="checkbox" name="vip" value="1" {{ old('vip', $guest->vip) ? 'checked' : '' }} /> VIP
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-secondary-foreground">
                    <input type="checkbox" id="blacklisted_toggle" name="blacklisted" value="1" {{ old('blacklisted', $guest->blacklisted) ? 'checked' : '' }} /> Blacklisted
                </label>
            </div>

            <div class="lg:col-span-2" id="blacklist_fields" style="display: none;">
                <div class="grid gap-3 grid-cols-1 lg:grid-cols-2">
                    <div class="lg:col-span-2">
                        <label class="text-sm text-secondary-foreground required-label">Blacklist reason</label>
                        <textarea class="kt-input w-full" name="blacklist_reason" rows="2">{{ old('blacklist_reason', $guest->blacklist?->reason) }}</textarea>
                    </div>
                    <div>
                        <label class="text-sm text-secondary-foreground">Blocked until</label>
                        <input type="date" class="kt-input w-full" name="blacklist_blocked_until" value="{{ old('blacklist_blocked_until', optional($guest->blacklist?->blocked_until)->format('Y-m-d')) }}" />
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Notes</label>
                <textarea class="kt-input w-full" name="notes" rows="3">{{ old('notes', $guest->notes) }}</textarea>
            </div>

            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Update</button>
                <a class="kt-btn" href="{{ route('admin.guests.index') }}">Cancel</a>
            </div>
        </form>

        @push('scripts')
        <script>
        (function(){
            var toggle = document.getElementById('blacklisted_toggle');
            var fields = document.getElementById('blacklist_fields');
            if (!toggle || !fields) return;
            function sync(){ fields.style.display = toggle.checked ? 'block' : 'none'; }
            toggle.addEventListener('change', sync);
            sync();
        })();
        </script>
        @endpush
    </div>
</div>
@endsection
