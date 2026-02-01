@extends('admin.layouts.app')
@section('content')
<div class="kt-card">
    <div class="kt-card-header">
        <h3 class="kt-card-title">Walk-in Booking</h3>
        <div class="text-sm text-secondary-foreground">Quick create reservation for walk-in guests</div>
    </div>
    <div class="kt-card-content p-4">
        <form method="POST" action="{{ route('admin.reservations.walkin.store') }}" class="grid gap-3 grid-cols-1 lg:grid-cols-2">
            @csrf
            <div>
                <label class="text-sm text-secondary-foreground">Guest name</label>
                <input class="kt-input w-full" name="guest_name" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Select Room(s)</label>
                    <input type="checkbox" id="select-all-rooms" />
                    <label for="select-all-rooms" class="text-sm">Select all</label>

                <div class="room-multiselect relative">
                    <div class="border rounded p-2 flex flex-wrap items-center gap-2" id="rooms-multiselect-box">
                        <div id="selected-rooms" class="flex flex-wrap gap-2"></div>
                        <input id="rooms-search-input" type="text" class="kt-input flex-1 min-w-[150px]" placeholder="Type to search rooms...">
                    </div>
                    <ul id="rooms-options" class="absolute z-50 bg-white border rounded mt-1 w-full max-h-48 overflow-auto hidden"></ul>
                </div>

                <div id="rooms-hidden-inputs">
                    @if(is_array(old('room_ids')))
                        @foreach(old('room_ids') as $rid)
                            <input type="hidden" name="room_ids[]" value="{{ $rid }}" />
                        @endforeach
                    @endif
                </div>

                <input type="hidden" name="room_number" value="" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Check-in</label>
                <input type="date" class="kt-input w-full" name="check_in_date" />
            </div>
            <div>
                <label class="text-sm text-secondary-foreground">Check-out</label>
                <input type="date" class="kt-input w-full" name="check_out_date" />
            </div>
            <div class="lg:col-span-2 flex gap-2">
                <button type="submit" class="kt-btn kt-btn-primary">Create</button>
                <a class="kt-btn" href="{{ route('admin.reservations.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    var selectAll = document.getElementById('select-all-rooms');
    var searchInput = document.getElementById('rooms-search-input');
    var optionsList = document.getElementById('rooms-options');
    var selectedBox = document.getElementById('selected-rooms');
    var hiddenContainer = document.getElementById('rooms-hidden-inputs');

    // build options from server-side data
    var rooms = [
        @foreach($availableRooms as $r)
        { id: '{{ $r->id }}', label: '{{ addslashes($r->number) }} — {{ addslashes($r->type ?? 'Room') }} (Capacity: {{ $r->capacity ?? '-' }})' },
        @endforeach
    ];

    // helper: render options list (filtered)
    function renderOptions(filter){
        optionsList.innerHTML = '';
        var f = filter ? filter.toLowerCase() : '';
        rooms.forEach(function(r){
            if (f && r.label.toLowerCase().indexOf(f) === -1) return;
            var li = document.createElement('li');
            li.className = 'p-2 hover:bg-muted/10 cursor-pointer';
            li.textContent = r.label;
            li.dataset.id = r.id;
            li.addEventListener('click', function(){ addRoom(r.id, r.label); hideOptions(); });
            optionsList.appendChild(li);
        });
        if (optionsList.children.length) optionsList.classList.remove('hidden'); else optionsList.classList.add('hidden');
    }

    function showOptions(){ renderOptions(searchInput.value); }
    function hideOptions(){ optionsList.classList.add('hidden'); }

    // add selected tag and hidden input
    function addRoom(id, label){
        // avoid duplicates
        if (hiddenContainer.querySelector('input[value="'+id+'"]')) return;
        var tag = document.createElement('span');
        tag.className = 'inline-flex items-center gap-2 px-2 py-1 rounded bg-muted/10';
        tag.textContent = label;
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'ml-2 text-sm';
        btn.textContent = '×';
        btn.addEventListener('click', function(){ tag.remove(); var inp = hiddenContainer.querySelector('input[value="'+id+'"]'); if (inp) inp.remove(); });
        tag.appendChild(btn);
        selectedBox.appendChild(tag);

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'room_ids[]';
        input.value = id;
        hiddenContainer.appendChild(input);
    }

    // preload old selections
    var oldInputs = hiddenContainer.querySelectorAll('input[name="room_ids[]"]');
    oldInputs.forEach(function(i){
        var rid = i.value;
        var found = rooms.find(function(r){ return r.id == rid; });
        if (found) addRoom(found.id, found.label);
    });

    // search interactions
    searchInput.addEventListener('input', function(e){ renderOptions(e.target.value); });
    searchInput.addEventListener('focus', showOptions);
    document.addEventListener('click', function(e){ if (!document.querySelector('.room-multiselect').contains(e.target)) hideOptions(); });

    // select all behavior
    if (selectAll){
        selectAll.addEventListener('change', function(){
            if (selectAll.checked){ rooms.forEach(function(r){ addRoom(r.id, r.label); }); }
            else { selectedBox.innerHTML = ''; hiddenContainer.innerHTML = ''; }
        });
    }
});
</script>
@endpush
