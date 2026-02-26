 <!-- Card 1 -->
@if($rooms->isEmpty())
                
<div class="text-center group">
    <div class="relative overflow-hidden rounded-2xl bg-slate-100 shadow-sm">
    <img
        src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80"
        alt="Double Deluxe Room"
        class="h-56 w-full object-cover transition-transform duration-500 ease-out group-hover:scale-110"
    />
    <div class="absolute inset-x-0 bottom-4 flex justify-center">
        <a
        href="#"
        class="inline-flex h-11 items-center justify-center px-10 bg-amber-500 text-white font-extrabold text-sm shadow hover:bg-amber-400 transition"
        >
        BOOK NOW
        </a>
    </div>
    </div>
    <h3 class="mt-6 text-base font-extrabold">Double Deluxe Room</h3>
    <div class="mt-2">
    <span class="text-sky-500 text-2xl font-extrabold">$250</span>
    <span class="text-sky-300 text-sm font-semibold">/night</span>
    </div>
</div>

@else
    @foreach($rooms as $room)
    @php
        $image = null;

        if (!empty($room->room[0]->avatar)) {
        $images = json_decode($room->room[0]->avatar, true);
        $image = is_array($images) ? $images[0] ?? null : null;
        }
    
    @endphp
    <div class="text-center group">
        @if($image)
        <div class="relative overflow-hidden rounded-2xl bg-slate-100 shadow-sm">
            <img
                src="{{ asset('public/storage/' . $image)}}"
                alt="{{ $room->name }}"
                class="h-56 w-full object-cover transition-transform duration-500 ease-out group-hover:scale-110"
            />
            <div class="absolute inset-x-0 bottom-4 flex justify-center">
                <a
                    href="#"
                    class="inline-flex h-11 items-center justify-center px-10 bg-amber-500 text-white font-extrabold text-sm shadow hover:bg-amber-400 transition"
                >
                    BOOK NOW
                </a>
            </div>
        </div>
        @endif
        <h3 class="mt-6 text-base font-extrabold">{{ $room->name }}</h3>
    
        <div class="mt-2">
            <span class="text-sky-500 text-2xl font-extrabold">${{ $room->base_price }}</span>
            <span class="text-sky-300 text-sm font-semibold">/night</span>
        </div>
    </div>
@endforeach
@endif