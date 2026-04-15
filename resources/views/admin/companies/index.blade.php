@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Companies</h3>
            <div class="text-sm text-secondary-foreground">Manage corporate guests</div>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('admin.companies.index') }}" class="flex items-center gap-2">
                <input type="text" name="q" class="kt-input" placeholder="Search" value="{{ request('q') }}" />
                <button class="kt-btn" type="submit">Search</button>
            </form>
            <a href="{{ route('admin.companies.create') }}" class="kt-btn kt-btn-primary">Add Company</a>
        </div>
    </div>

    <div class="kt-card-content p-4">
        @if(session('success'))
            <div class="mb-4 p-3 bg-success/10 text-success rounded">{{ session('success') }}</div>
        @endif

        <table class="w-full text-left table-auto">
            <thead>
                <tr class="text-sm text-secondary-foreground">
                    <th class="p-2">SL</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Contact</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Phone</th>
                    <th class="p-2">Tax #</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $c)
                    <tr class="border-t hover:bg-muted/10">
                        <td class="p-2">{{ $companies->firstItem() ? $companies->firstItem() + $loop->index : $loop->iteration }}</td>
                        <td class="p-2">{{ $c->name }}</td>
                        <td class="p-2">{{ $c->contact_person ?? '-' }}</td>
                        <td class="p-2">{{ $c->email ?? '-' }}</td>
                        <td class="p-2">{{ $c->phone ?? '-' }}</td>
                        <td class="p-2">{{ $c->tax_number ?? '-' }}</td>
                        <td class="p-2">
                            <a class="kt-btn kt-btn-sm" href="{{ route('admin.companies.edit', $c->id) }}">Edit</a>
                            <form action="{{ route('admin.companies.destroy', $c->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this company?');">
                                @csrf
                                @method('DELETE')
                                <button class="kt-btn kt-btn-danger kt-btn-sm" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-secondary-foreground">No companies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $companies->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
@endsection
