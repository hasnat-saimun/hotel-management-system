@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Add Company</h3>
            <div class="text-sm text-secondary-foreground">Create company profile</div>
        </div>
        <a class="kt-btn" href="{{ route('admin.companies.index') }}">Back</a>
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

        <form method="POST" action="{{ route('admin.companies.store') }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf

            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground required-label">Name</label>
                <input type="text" class="kt-input w-full" name="name" value="{{ old('name') }}" required />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Contact person</label>
                <input type="text" class="kt-input w-full" name="contact_person" value="{{ old('contact_person') }}" />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Email</label>
                <input type="email" class="kt-input w-full" name="email" value="{{ old('email') }}" />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Phone</label>
                <input type="text" class="kt-input w-full" name="phone" value="{{ old('phone') }}" />
            </div>

            <div>
                <label class="text-sm text-secondary-foreground">Tax number</label>
                <input type="text" class="kt-input w-full" name="tax_number" value="{{ old('tax_number') }}" />
            </div>

            <div class="lg:col-span-2">
                <label class="text-sm text-secondary-foreground">Address</label>
                <textarea class="kt-input w-full" name="address" rows="2">{{ old('address') }}</textarea>
            </div>

            <div class="lg:col-span-2 flex gap-2">
                <button class="kt-btn kt-btn-primary" type="submit">Save</button>
                <a class="kt-btn" href="{{ route('admin.companies.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
