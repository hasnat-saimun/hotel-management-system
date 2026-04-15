@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Edit Travel Agent</h3>
            <div class="text-sm text-secondary-foreground">Update agent profile</div>
        </div>
        <a class="kt-btn" href="{{ route('admin.travel-agents.index') }}">Back</a>
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

        <form method="POST" action="{{ route('admin.travel-agents.update', $agent->id) }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            @method('PUT')

            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground required-label">Name</label>
                <input type="text" class="kt-input w-full" name="name" value="{{ old('name', $agent->name) }}" required />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Email</label>
                <input type="email" class="kt-input w-full" name="email" value="{{ old('email', $agent->email) }}" />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Phone</label>
                <input type="text" class="kt-input w-full" name="phone" value="{{ old('phone', $agent->phone) }}" />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Commission percentage</label>
                <input type="number" step="0.01" min="0" max="100" class="kt-input w-full" name="commission_percentage" value="{{ old('commission_percentage', $agent->commission_percentage) }}" />
            </div>

            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Address</label>
                <textarea class="kt-input w-full" name="address" rows="2">{{ old('address', $agent->address) }}</textarea>
            </div>

            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Update</button>
                <a class="kt-btn" href="{{ route('admin.travel-agents.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
