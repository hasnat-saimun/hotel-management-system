@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header flex items-center justify-between">
        <div>
            <h3 class="kt-card-title">Extra Services</h3>
            <div class="text-sm text-secondary-foreground">Manage extra services (late checkout, breakfast etc.)</div>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('admin.rooms.services.index') }}" id="services-search-form" class="flex items-center gap-2">
                <input id="services-search-input" type="text" name="q" class="kt-input" placeholder="Search services..." value="{{ request('q') }}" />
            </form>
            <div>
                <a href="{{ route('admin.rooms.services.create') }}" class="kt-btn kt-btn-primary">Add Service</a>
            </div>
        </div>
    </div>
    <div class="kt-card-content p-4">
        <table class="w-full text-left table-auto">
            <thead>
                <tr class="text-sm text-secondary-foreground">
                    <th class="p-2">Name</th>
                    <th class="p-2">Price</th>
                    <th class="p-2">Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $s)
                <tr class="border-t hover:bg-muted/10">
                    <td class="p-2">{{ $s->name }}</td>
                    <td class="p-2">{{ number_format($s->price,2) }}</td>
                    <td class="p-2">{{ $s->description }}</td>
                    <td class="p-2">
                        <a class="kt-btn kt-btn-sm" href="{{ route('admin.rooms.services.edit', $s->id) }}">Edit</a>
                        <form action="{{ route('admin.rooms.services.destroy', $s->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this service?');">
                            @csrf
                            @method('DELETE')
                            <button class="kt-btn kt-btn-danger kt-btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-secondary-foreground">No data found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $services->appends(request()->except('page'))->links() }}
        </div>
    @push('scripts')
    <script>
    (function(){
        var form = document.getElementById('services-search-form');
        var input = document.getElementById('services-search-input');
        if (!form || !input) return;
        var timeout = null;
        input.addEventListener('input', function(){
            if (timeout) clearTimeout(timeout);
            timeout = setTimeout(function(){ form.submit(); }, 500);
        });
    })();
    </script>
    @endpush
        </table>
    </div>
</div>
@endsection
