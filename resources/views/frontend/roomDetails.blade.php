@extends('frontend.layout')
@push('styles')
<style>
.step-link{
  cursor:pointer;
  padding:8px 12px;
  border-radius:10px;
  color:#fffafa;
  transition:.2s ease;

}
/* //  background-color: #9c968c; */

.step-active{
  
  color: rgba(255, 250, 250, 0.6);
}
.tab-circle{
    background-color: #fff;
    color:#000
}
.step-active .tab-circle{
    background-color: #9c968c;
    color:#fff
}

    </style>
@endpush
@section('content')
  <!-- STEPS BAR -->
  <section class=" bg-[#f9fbff] text-slate-700 py-10">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <div class="bg-[#d6cec3] px-10 py-4">
        <div class="flex items-center justify-between text-sm font-semibold text-white">

          <!-- Step 1 -->
          <div class="step-link flex items-center gap-3 tab1" id="firstTab">
            <span class="flex h-7 w-7 items-center justify-center rounded-full tab-circle text-xs font-bold">
              1
            </span>
            <span>Rooms and Rates</span>
          </div>

          <!-- Divider -->
          <div class="flex-1 mx-4 h-px bg-white/40"></div>

          <!-- Step 2 (Active) -->
          <div class="step-link flex items-center gap-3 rounded-tr-lg tab2">
            <span class="flex h-7 w-7 items-center justify-center rounded-full  tab-circle text-xs font-bold">
              2
            </span>
            <span>Guest Details</span>
          </div>

          <!-- Divider -->
          <div class="flex-1 mx-4 h-px bg-white/40"></div>

          <!-- Step 3 -->
          <div class="step-link flex items-center gap-3 tab3">
            <span class="flex h-7 w-7 items-center justify-center rounded-full tab-circle text-xs font-bold">
              3
            </span>
            <span>Confirmation</span>
          </div>

        </div>
      </div>

    </div>
  </section>

  <!-- Option 1 -->
  <section class=" bg-[#f9fbff] text-slate-700 py-10 tabcontent hidden" id="tab1">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        <!-- LEFT SIDEBAR -->
        <aside class="lg:col-span-3 space-y-6">

          <!-- Your Stay box -->
            <form action="" method="POST">
                @csrf   
                <div class="bg-[#efe7dc] p-6">
                    <h3 class="text-center text-2xl font-semibold text-slate-600">Your Stay</h3>

                    <div class="mt-6 space-y-5 text-sm">
                    <!-- Arrival -->
                    <div>
                        <label class="block text-slate-500 mb-2">Arrival Date</label>
                        <div class="relative">
                        <input
                            type="date"
                            value="{{ $data['check_in_date']}}"
                            name="check_in_date"
                            class="w-full h-11 bg-white border border-slate-300 px-3 pr-10 text-slate-600 outline-none"
                            
                        />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500">
                            📅
                        </span>
                        </div>
                    </div>

                    <!-- Departure -->
                    <div>
                        <label class="block text-slate-500 mb-2">Departure Date</label>
                        <div class="relative">
                        <input
                            type="date"
                            value="{{$data['check_out_date']}}"
                            name="check_out_date"
                            class="w-full h-11 bg-white border border-slate-300 px-3 pr-10 text-slate-600 outline-none"
                            
                        />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500">
                            📅
                        </span>
                        </div>
                    </div>

                    <!-- Nights -->
                    <div>
                        <label class="block text-slate-500 mb-2">Nights</label>
                        <input
                        type="number"
                        name="nights"
                        value="{{ \Carbon\Carbon::parse($data['check_in_date'])->diffInDays(\Carbon\Carbon::parse($data['check_out_date'])) }}"
                        class="w-full h-11 bg-white border border-slate-300 px-3 text-slate-600 outline-none"
                            readonly
                        />
                        </select>
                    </div>

                    <!-- Adults -->
                    <div>
                        <label class="block text-slate-500 mb-2">
                        Adults <span class="text-xs text-slate-400">16+ years</span>
                        </label>
                        <input
                        type="number"
                        value="{{ $data['adults'] }}"
                        class="w-full h-11 bg-white border border-slate-300 px-3 text-slate-600 outline-none"
                        name="adults">
                    </div>
                    <div>
                        <label class="block text-slate-500 mb-2">
                        Children <span class="text-xs text-slate-400">0-15 years</span>
                        </label>
                        <input
                        type="number"
                        value="{{ $data['children'] }}"
                        class="w-full h-11 bg-white border border-slate-300 px-3 text-slate-600 outline-none"
                        name="children">
                    </div>

                    <button
                        class="w-full h-11 bg-[#8d8a84] text-white font-semibold hover:bg-[#7c7973] transition"
                        type="submit"
                    >
                        Check availability
                    </button>
                    </div>
                </div>
            </form>

          <!-- Gift Cards box -->
          <div class="bg-[#efe7dc] p-6 text-sm text-slate-600">
            <h4 class="text-center font-semibold text-slate-700">Gift Cards</h4>
            <p class="mt-4 leading-6 text-slate-500">
              Our qualia digital gift cards are the perfect way to treat a friend, family or client. With a range
              of dollar values available, they can be redeemed at numerous locations across Hamilton Island.
              <a href="#" class="underline text-slate-600 hover:text-slate-800">Purchase a qualia Gift Card here.</a>
            </p>
          </div>

        </aside>

        <!-- RIGHT CONTENT -->
        <main class="lg:col-span-9">

          <!-- Top right link -->
                @if(!$rooms->isEmpty())
                <!-- Available notice -->
                <div class="bg-[#7f7d73] text-white px-5 py-3 text-sm flex items-center gap-3">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white/20">✓</span>
                    <span>@php echo count($rooms); @endphp available rooms match your dates and search criteria</span>
                </div>

                <div class="mt-6 space-y-6">
                <!-- Room cards -->
                    @foreach($rooms as $room)
                        @php
                                $image = null;

                            if (!empty($room->avatar)) {
                            $images = json_decode($room->avatar, true);
                            $image = is_array($images) ? ($images[0] ?? null) : null;
                                }

                            
                        @endphp
                        
                        <!-- Card 1 -->
                        <article class="bg-white border border-slate-200 p-5">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                            <div class="md:col-span-3">
                            @if($image)
                                <img
                                    class="w-full h-28 md:h-32 object-cover"
                                    src="{{ asset('public/storage/' . $image)}}"
                                alt="{{ optional($room->roomType)->name }}"
                            />
                            @endif
                            </div>

                            <div class="md:col-span-9">
                            <div class="flex items-start justify-between gap-6">
                            <h3 class="text-xl font-semibold text-slate-700">{{ optional($room->roomType)->name }}</h3>
                                <div class="text-sm text-slate-500">
                                Total from <span class="font-semibold text-slate-700">{{ optional($room->roomType)->base_price }}</span>
                                </div>
                            </div>

                            <p class="mt-3 text-sm leading-6 text-slate-500">
                                {{ optional($room->roomType)->description }}
                            </p>

                            <div class="mt-4 flex flex-wrap items-center justify-between gap-4 text-sm">
                                <a href="#" id="openBtn" class="underline text-slate-500 hover:text-slate-700">
                                Features, floor plan &amp; gallery
                                </a>

                                <div class="flex items-center gap-2 text-slate-500">
                                <span>Max guests 👥A- {{ optional($room->roomType)->capacity_adults }} : 👥C- {{ optional($room->roomType)->capacity_children }}</span>
                                </div>

                                <div class="flex items-center gap-4">
                                <span class="text-[#b3564a] font-semibold">Hurry, only 1 left!</span>
                                <button
                                    type="button"
                                    class="h-10 px-5 bg-[#6d7a64] text-white text-sm font-semibold hover:bg-[#5f6b57] transition"
                                    data-rates-toggle
                                    aria-expanded="false"
                                >
                                    <span data-rates-icon>▾</span>
                                    <span class="ml-2" data-rates-label>Show available rates</span>
                                </button>
                                </div>
                            </div>

                            </div>

                            <div class="md:col-span-12">
                            <!-- Rates dropdown (smooth open/close) -->
                            <div
                                class="mt-5 overflow-hidden h-0 opacity-0 pointer-events-none transition-[height,opacity] duration-300 ease-out"
                                data-rates-panel
                                data-open="false"
                            >
                                <div class="border border-[#cfc6b7] bg-white">

                                <!-- top right link -->
                                <div class="flex justify-end px-5 py-3 text-sm text-slate-600">
                                    <a href="#" class="flex items-center gap-2 underline underline-offset-2 hover:text-slate-900">
                                    <span class="text-slate-500">📅</span>
                                    <span>View availability and rates</span>
                                    </a>
                                </div>

                                <!-- ITEM 1 -->
                                <div class="bg-[#fbf7ef] px-6 py-5 border-t border-[#e3dbcf]">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:items-start">

                                    <!-- left -->
                                    <div class="md:col-span-7">
                                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                                        <h3 class="text-lg font-semibold text-slate-700">Classic Rate</h3>

                                        <div class="text-sm text-slate-500 flex gap-4">
                                            <a href="#" class="underline underline-offset-2 hover:text-slate-800">Inclusions</a>
                                            <a href="#" class="underline underline-offset-2 hover:text-slate-800">Terms and Conditions</a>
                                        </div>
                                        </div>

                                        <p class="mt-4 text-sm leading-6 text-slate-500 max-w-xl">
                                        Includes a la carte breakfast daily, all non-alcoholic beverages at qualia,
                                        use of a golf buggy for the duration of your stay and more.
                                        </p>

                                        <p class="mt-4 text-sm font-semibold text-[#6d7a64]">
                                        FREE cancellation until 1 March 2026
                                        </p>
                                    </div>

                                    <!-- right -->
                                    <div class="md:col-span-5 md:text-right">
                                        <div class="text-sm text-slate-600">
                                        Total <span class="font-semibold">BDT {{ optional($room->roomType)->base_price }}</span>
                                        <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-slate-200 text-[10px] text-slate-600 ml-1">i</span>
                                        </div>

                                        <button
                                        onclick="openTab('tab2', 'tab2',{{ $room->id }})"
                                        type="button"
                                        class="mt-4 inline-flex items-center justify-center gap-2 h-10 px-6 bg-[#6d7a64] text-white text-sm font-semibold hover:bg-[#5f6b57] transition"
                                        >
                                        <span>Book now {{$room->id}} </span>
                                        <span class="text-lg leading-none">›</span>
                                        </button>
                                    </div>

                                    </div>
                                </div>

                                <!-- ITEM 2 -->
                                <div class="bg-[#fbf7ef] px-6 py-5 border-t border-[#e3dbcf]">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:items-start">

                                    <!-- left -->
                                    <div class="md:col-span-7">
                                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                                        <h3 class="text-lg font-semibold text-slate-700">Gourmet Rate</h3>

                                        <div class="text-sm text-slate-500 flex gap-4">
                                            <a href="#" class="underline underline-offset-2 hover:text-slate-800">Inclusions</a>
                                            <a href="#" class="underline underline-offset-2 hover:text-slate-800">Terms and Conditions</a>
                                        </div>
                                        </div>

                                        <p class="mt-4 text-sm leading-6 text-slate-500 max-w-xl">
                                        Includes complimentary a la carte breakfast and dinner daily, all non-alcoholic beverages at qualia,
                                        use of a golf buggy for the duration of your stay and more.
                                        </p>

                                        <p class="mt-4 text-sm font-semibold text-[#6d7a64]">
                                        FREE cancellation until 1 March 2026
                                        </p>
                                    </div>

                                    <!-- right -->
                                    <div class="md:col-span-5 md:text-right">
                                        <div class="text-sm text-slate-600">
                                        Total <span class="font-semibold">AUD $8,640.00</span>
                                        <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-slate-200 text-[10px] text-slate-600 ml-1">i</span>
                                        </div>

                                        <button
                                        type="button"
                                        class="mt-4 inline-flex items-center justify-center gap-2 h-10 px-6 bg-[#6d7a64] text-white text-sm font-semibold hover:bg-[#5f6b57] transition"
                                        >
                                        <span>Book now</span>
                                        <span class="text-lg leading-none">›</span>
                                        </button>
                                    </div>

                                    </div>
                                </div>

                                <!-- bottom right link -->
                                <div class="flex justify-end px-6 py-3 bg-white border-t border-[#e3dbcf] text-sm text-slate-500">
                                    <a href="#" class="flex items-center gap-2 underline underline-offset-2 hover:text-slate-800">
                                    <span class="text-slate-500">▾</span>
                                    <span>Show all packages</span>
                                    </a>
                                </div>
                                <!-- Booking Benefits Box (TailwindCSS) -->
                                <section class="py-8">
                                    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

                                    <div class="border border-[#cfc6b7] bg-white px-6 py-5">

                                        <ul class="space-y-3 text-sm text-slate-600">

                                        <li class="flex items-start gap-3">
                                            <span class="mt-0.5 text-[#6d7a64]">✓</span>
                                            <span>
                                            Book direct now, and you'll earn a minimum of
                                            <strong>6,840 Velocity Frequent Flyer Points</strong>
                                            </span>
                                        </li>

                                        <li class="flex items-start gap-3">
                                            <span class="mt-0.5 text-[#6d7a64]">✓</span>
                                            <span>
                                            No booking or credit card fees, when booking direct
                                            </span>
                                        </li>

                                        <li class="flex items-start gap-3">
                                            <span class="mt-0.5 text-[#6d7a64]">✓</span>
                                            <span>
                                            Flexible Cancellation: Secure your booking with a fully refundable deposit*
                                            </span>
                                        </li>

                                        <li class="flex items-start gap-3">
                                            <span class="mt-0.5 text-[#6d7a64]">✓</span>
                                            <span>
                                            Complimentary a la carte breakfast at the Long Pavilion included daily per person
                                            </span>
                                        </li>

                                        <li class="flex items-start gap-3">
                                            <span class="mt-0.5 text-[#6d7a64]">✓</span>
                                            <span>
                                            You will always receive the best rate when booking direct with qualia.
                                            <strong>It's our guarantee</strong>
                                            </span>
                                        </li>

                                        </ul>

                                    </div>

                                    </div>
                                </section>

                            </div>
                            </div>
                            </div>

                        </div>
                        </article>
                    @endforeach
                
                </div>
                @else
                <!-- not available card -->
                    <section class="bg-[#fbf3e8] py-4">
                        <div class="mx-auto max-w-6xl px-6">

                            <p class="text-sm md:text-base leading-7 text-[#6f675d]">
                            Unfortunately, <span class="font-medium">qualia</span> is fully booked for your selected dates.
                            Please use the
                            <span class="font-medium">“Change dates”</span> buttons below to view availability.
                            </p>

                            <p class="mt-2 text-sm md:text-base leading-7 text-[#6f675d]">
                            Alternatively,
                            <a href="#" class="font-medium underline hover:text-[#4f463b] transition">
                                Beach Club
                            </a>
                            is available for your selected dates.
                            <a href="#" class="font-medium underline hover:text-[#4f463b] transition">
                                Click here
                            </a>
                            to find out more.
                            </p>

                        </div>
                    </section>
                @endif
          <!-- Not available notice -->
          <div class="mt-10 bg-[#a45a4e] text-white px-5 py-3 text-sm flex items-center gap-3">
            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white/20">✕</span>
            <span>The following rooms are not available based on your dates or search criteria</span>
          </div>

          <!-- Not available card -->
          <div class="mt-6 bg-white border border-slate-200 p-5">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
              <div class="md:col-span-3">
                <img
                  class="w-full h-28 md:h-32 object-cover"
                  src="https://images.unsplash.com/photo-1521783593447-5702b9bfd267?auto=format&fit=crop&w=800&q=80"
                  alt="Room"
                />
              </div>

              <div class="md:col-span-9">
                <div class="flex items-start justify-between gap-6">
                  <h3 class="text-xl font-semibold text-slate-700">Windward Accessible Pavilion</h3>
                  <div class="text-sm font-semibold text-[#a45a4e]">
                    No availability over your dates
                  </div>
                </div>

                <p class="mt-3 text-sm leading-6 text-slate-500">
                  qualia offers a Windward Pavilion that has been configured for easy wheelchair access, as well as providing
                  all the classic Windward Pavilion facilities.
                </p>

                <div class="mt-4 flex flex-wrap items-center justify-between gap-4 text-sm">
                  <a href="#" class="underline text-slate-500 hover:text-slate-700">
                    Features, floor plan &amp; gallery
                  </a>

                  <div class="flex items-center gap-2 text-slate-500">
                    <span>Max guests</span>
                    <span>👥👥</span>
                  </div>

                  <button class="h-10 px-5 bg-[#6d7a64] text-white text-sm font-semibold hover:bg-[#5f6b57] transition">
                    Change dates
                  </button>
                </div>

              </div>
            </div>
          </div>

        </main>

      </div>
    </div>
  </section>


  <!-- OPTION 2  -->
<section class="bg-[#f9fbff] text-slate-700 py-10 tabcontent hidden" id="tab2">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- LEFT SUMMARY SIDEBAR -->
        <aside class="lg:col-span-3 space-y-4">
          <div class="bg-[#eee6db] border border-slate-200">
            <div class="p-4 border-b border-slate-200 flex items-center justify-between">
              <div class="font-semibold text-slate-700">Your stay</div>
              <a href="#" class="text-xs underline text-slate-500 hover:text-slate-700" id="tab1">Modify</a>
            </div>

            <div class="p-4 text-xs space-y-4">
              <div>
                <div class="font-semibold text-slate-500">ARRIVAL</div>
                <div class="text-slate-600">{{ $data['check_in_date'] }}</div>
              </div>

              <div>
                <div class="font-semibold text-slate-500">DEPARTURE</div>
                <div class="text-slate-600">{{ $data['check_out_date'] }}</div>
              </div>

              <div>
                <div class="font-semibold text-slate-500">NIGHTS</div>
                <div class="text-slate-600">{{ \Carbon\Carbon::parse($data['check_in_date'])->diffInDays(\Carbon\Carbon::parse($data['check_out_date'])) }} nights</div>
              </div>

              <div>
                <div class="font-semibold text-slate-500">GUESTS</div>
                <div class="text-slate-600">{{ $data['adults'] }} adults, {{ $data['children'] }} children</div>
              </div>
            @if(!$rooms->isEmpty())
              <div>
                <div class="font-semibold text-slate-500">ROOM</div>
                <div class="text-slate-600">{{ optional($rooms->first())->roomType->name }}</div>
              </div>

              <div>
                <div class="font-semibold text-slate-500">RATE</div>
                <div class="text-slate-600">BDT {{ optional($rooms->first())->roomType->base_price }}</div>
              </div>

              <div class="pt-4 border-t border-slate-200 space-y-3">
                <div>
                  <div class="font-semibold text-slate-500">TOTAL COST</div>
                  <div class="text-slate-700">Working</div>
                </div>
                <div>
                  <div class="font-semibold text-slate-500">BALANCE DUE 7 DAYS PRIOR</div>
                  <div class="text-slate-700">Working</div>
                </div>
                @endif
              </div>

              <div class="bg-[#d9d0c3] px-3 py-4 -mx-4">
                <div class="font-semibold text-slate-600">DEPOSIT PAYABLE TODAY</div>
                <div class="text-lg font-semibold text-slate-700">BDT Working</div>
              </div>

              <ul class="space-y-2 text-[11px] text-slate-500 pt-2">
                <li class="flex gap-2"><span class="text-emerald-600">✓</span> Earn 6,840 Velocity Frequent Flyer Points</li>
                <li class="flex gap-2"><span class="text-emerald-600">✓</span> No booking or credit card fees</li>
                <li class="flex gap-2"><span class="text-emerald-600">✓</span> FREE Cancellation before 1 March, 2026</li>
                <li class="flex gap-2"><span class="text-emerald-600">✓</span> Complimentary a la carte breakfast at the Long Pavilion</li>
                <li class="flex gap-2"><span class="text-emerald-600">✓</span> You will always receive the best rate when booking direct with qualia. It’s our guarantee.</li>
              </ul>
            </div>
          </div>
        </aside>

        <!-- MAIN FORM CONTENT -->
        <main class="lg:col-span-9">
            <form action="{{ route('booking.store') }}" method="POST">
                @csrf

                <!-- //you stay data -->
                 @if(!$rooms->isEmpty())
                    <input type="text" name="check_in_date" value="{{ $data['check_in_date'] }}">
                    <input type="text" name="check_out_date" value="{{ $data['check_out_date'] }}">
                    <input type="text" name="adults" value="{{ $data['adults'] }}">
                    <input type="text" name="children" value="{{ $data['children'] }}">
                    <input type="text" name="room_id" value="{{ optional($rooms->first())->id }}">
                    <input type="text" name="room_type_id" value="{{ optional($rooms->first())->room_type_id }}">
                @endif
                <!-- Header -->
                <div class="flex items-start justify-between gap-6">
                    <h1 class="text-2xl font-semibold text-slate-700">Reservation Information</h1>
                    <div class="text-xs text-slate-400 mt-1">* Denotes a required field</div>
                </div>

                <!-- Guest Details -->
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-slate-600 mb-3">Guest Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    

                    <input class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="First Name *" name="first_name" required />
                    <input class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Last Name *" name="last_name" required />

                    <input class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Email Address *" name="email" required/>
                    <input type="number" class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Contact Number" name="phone" required/>
                    </div>
                </div>

                <!-- Address -->
                <div class="mt-8">
                    <h3 class="text-sm font-semibold text-slate-600 mb-3">Address</h3>

                    <div class="">
                    
                    <textarea  rows="3"
                    class="mt-4 w-full border bg-white border-slate-300 px-3 py-3 text-sm"placeholder=" Full In Your Address Line 1 *" name="address" required></textarea>

                    </div>

                    <div class="mt-4 text-xs text-slate-500 font-semibold">
                    Virgin Australia Velocity Frequent Flyer Number (optional)
                    </div>

                    <div class="mt-2 flex flex-col md:flex-row md:items-center gap-3">
                    <select class="md:col-span-2 h-11 border bg-white border-slate-300 px-3 text-sm" name="id_type" required>
                        <option value="">ID Type *</option>
                        <option value="ID">ID</option>
                        <option value="NID">NID</option>
                        <option value="Passport">Passport</option>
                    </select>
                    <input class="h-11 w-full md:w-64 border bg-white border-slate-300 px-3 text-sm" placeholder="10 Digit Id Number" name="id_number" required />
                    <div class="text-xs text-slate-500 flex items-center gap-2">
                        <span class="text-emerald-600">✓</span>
                        <span>Earn 6,840 Velocity Frequent Flyer Points</span>
                    </div>
                    </div>
                </div>

                <!-- Additional Guest Details -->
                <!-- <div class="mt-10">
                    <h3 class="text-sm font-semibold text-slate-600 mb-3">Additional Guest Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <select class="md:col-span-3 h-11 border bg-white border-slate-300 px-3 text-sm" required>
                        <option>Title *</option>
                        <option>Mr</option>
                        <option>Mrs</option>
                        <option>Ms</option>
                    </select>

                    <input class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="First Name *" required />
                    <input class="md:col-span-5 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Last Name *" required />
                    </div>
                </div> -->
                <!-- Dietary Requirements -->
                <!-- <div class="mt-12">
                    <h2 class="text-2xl font-semibold text-slate-600">Dietary Requirements</h2>
                    <p class="mt-2 text-xs text-slate-400">
                    Do you have any dietary requirements we should be aware of?
                    </p>

                    <textarea
                    rows="3"
                    class="mt-4 w-full border bg-white border-slate-300 px-3 py-3 text-sm"
                    placeholder="Enter your dietary requirements here. 1000 characters maximum."
                    ></textarea>
                </div> -->
                <!-- Special Requests -->
                <div class="mt-12">
                    <h2 class="text-2xl font-semibold text-slate-600">Special Requests</h2>
                    <p class="mt-2 text-xs text-slate-400">
                    Please let us know if you have any special requests for your pavilion, or if you are travelling for a special occasion.
                    </p>

                    <textarea
                    rows="3"
                    class="mt-4 w-full border bg-white border-slate-300 px-3 py-3 text-sm"
                    placeholder="Enter your comments here. 1000 characters maximum." name="note"
                    required
                    ></textarea>
                </div>

                <!-- Arrival and Departure Information -->
                <!-- <div class="mt-12">
                    <h2 class="text-2xl font-semibold text-slate-600">Arrival and Departure Information</h2>
                    <p class="mt-2 text-xs text-slate-400">
                    Providing your arrival and departure details will help ensure your VIP transfer service is confirmed for your arrival and departure.
                    </p>

                    <div class="mt-4 flex items-center gap-3">
                    <select class="h-11 w-full md:w-80 border bg-white border-slate-300 px-3 text-sm">
                        <option>Travel Method *</option>
                        <option>Flight</option>
                        <option>Car</option>
                        <option>Ferry</option>
                    </select>
                    <span class="text-slate-400 text-sm">ⓘ</span>
                    </div>
                </div> -->

                <!-- Secure Payment Method -->
                <!-- <div class="mt-12">
                    <h2 class="text-2xl font-semibold text-slate-600">Secure Payment Method</h2>

                    <div class="mt-4 border border-sky-200 bg-sky-50 px-4 py-3 text-xs text-slate-600">
                    <div class="font-semibold flex items-center gap-2">
                        <span class="text-amber-600">🔒</span>
                        <span>Secure Payment Form - encrypted by a 256-bit secure SSL connection</span>
                    </div>
                    <p class="mt-2 text-slate-500">
                        Your credit card information will be securely kept by Westpac Institutional Bank. When processing payment on this credit card, the Hamilton Island Reservations team will only have access to your Westpac customer number and not the credit card information.
                    </p>
                    </div>

                    <div class="mt-6 bg-[#fbf6ee] border border-slate-200 p-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 text-sm">

                        <div class="md:col-span-8">
                        <label class="block text-xs text-slate-500 mb-2">Card Number</label>
                        <input class="w-full h-11 border bg-white border-slate-300 px-3" placeholder="•••• •••• •••• ••••" />
                        </div>

                        <div class="md:col-span-4">
                        <label class="block text-xs text-slate-500 mb-2">Security Code</label>
                        <input class="w-full h-11 border bg-white border-slate-300 px-3" placeholder="•••" />
                        </div>

                        <div class="md:col-span-6">
                        <label class="block text-xs text-slate-500 mb-2">Name on Card</label>
                        <input class="w-full h-11 border bg-white border-slate-300 px-3" placeholder="Name on Card" />
                        </div>

                        <div class="md:col-span-6">
                        <label class="block text-xs text-slate-500 mb-2">Expiration</label>
                        <div class="flex gap-3">
                            <select class="h-11 w-24 border bg-white border-slate-300 px-3">
                            <option>MM</option>
                            <option>01</option><option>02</option><option>03</option><option>04</option>
                            <option>05</option><option>06</option><option>07</option><option>08</option>
                            <option>09</option><option>10</option><option>11</option><option>12</option>
                            </select>
                            <select class="h-11 w-24 border bg-white border-slate-300 px-3">
                            <option>YY</option>
                            <option>2026</option><option>2027</option><option>2028</option><option>2029</option>
                            <option>2030</option>
                            </select>
                        </div>
                        </div>

                    </div>

                    <div class="mt-5 text-xs text-slate-500 flex flex-wrap items-center gap-3">
                        <span class="text-slate-400">Accepted Methods of Payment</span>
                        <span class="px-2 py-1 bg-white border border-slate-200 text-[10px]">VISA</span>
                        <span class="px-2 py-1 bg-white border border-slate-200 text-[10px]">Mastercard</span>
                        <span class="px-2 py-1 bg-white border border-slate-200 text-[10px]">AMEX</span>
                        <span class="px-2 py-1 bg-white border border-slate-200 text-[10px]">Diners</span>
                    </div>
                    </div>

                    <div class="mt-4 bg-[#d6cec3] px-4 py-3 text-sm text-slate-700">
                    Deposit payable today <span class="font-semibold">$1,368.00</span>
                    <span class="text-xs text-slate-500">(refundable, including taxes)</span>
                    </div>
                </div> -->

                <!-- Booking Conditions -->
                <div class="mt-12">
                    <h2 class="text-2xl font-semibold text-slate-600">Booking Conditions</h2>

                    <div class="mt-4 space-y-4 text-sm text-slate-500">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" class="mt-1 h-4 w-4 border-slate-300" />
                        <span>
                        I have read and agree to the
                        <a href="#" class="underline">Deposit, Final Payment and Cancellation Policy</a>,
                        <a href="#" class="underline">General Booking Terms</a> and
                        <a href="#" class="underline">Privacy Policy</a> for this itinerary.*
                        </span>
                    </label>

                    <label class="flex items-start gap-3">
                        <input type="checkbox" class="mt-1 h-4 w-4 border-slate-300" />
                        <span>
                        I understand that qualia only caters to guests over the age of 16 years and all travelling guests on this booking are 16 or over.*
                        </span>
                    </label>

                    <label class="flex items-start gap-3">
                        <input type="checkbox" class="mt-1 h-4 w-4 border-slate-300" />
                        <span>
                        I am at least 18 years of age and at least one guest in my party will meet the minimum check-in age requirement (check-in age: 18) for the hotel upon arrival.
                        </span>
                    </label>
                    </div>

                    <div  class="mt-6 flex flex-col sm:flex-row sm:items-center gap-4">
                    <button type="submit" class="h-11 px-6 bg-[#6d7a64] text-white font-semibold hover:bg-[#5f6b57] transition">
                        Submit Booking
                    </button>
                    <div class="text-sm text-slate-500 flex items-center gap-2">
                        <span class="text-emerald-600">✓</span>
                        <span>You won't find a better price, it’s our guarantee</span>
                    </div>
                    </div>
                </div>
            </form>

        </main>

      </div>
    </div>
</section>

 

  


<!-- Room Details Modal str -->
   <!-- PAGE (dummy background) -->
  

  <!-- OVERLAY -->
  <div id="overlay" class="fixed inset-0 z-50 bg-black/60 hidden flex items-center justify-center p-4">
    <!-- MODAL -->
    <div class="w-full max-w-3xl bg-white shadow-lg border border-slate-200 relative">

      <!-- Top bar -->
      <div class="bg-[#efe7dc] px-6 py-4 flex items-center justify-center relative">
        <h2 class="text-xl font-semibold text-slate-600">Leeward Pavilion Room Details</h2>

        <!-- Close X -->
        <button id="closeX" class="absolute right-4 top-3 text-slate-400 hover:text-slate-600 text-2xl leading-none">
          ×
        </button>
      </div>

      <!-- Tabs -->
      <div class="px-6 pt-4">
        <div class="border-b border-slate-200 flex gap-2 text-sm">
          <button class="px-4 py-2 border border-slate-200 border-b-white bg-white text-slate-600">
            Overview
          </button>
          <button class="px-4 py-2 text-slate-500 hover:text-slate-700">
            Image Gallery
          </button>
          <button class="px-4 py-2 text-slate-500 hover:text-slate-700">
            Floor Plan
          </button>
        </div>
      </div>

      <!-- Scroll body -->
      <div class="px-6 py-5 h-[440px] overflow-y-auto">
        <p class="text-sm text-slate-600">
          <strong>(Minimum night stay applies)</strong><br />
          Elegantly designed and beautifully furnished, these one-bedroom pavilions have a private sundeck
          with eucalypts framing stunning tropical garden or water views.
        </p>

        <h3 class="mt-6 text-xl font-light text-slate-600">
          Why you’ll love the Leeward Pavilions:
        </h3>

        <ul class="mt-4 space-y-2 text-sm text-slate-600">
          <li>— qualia is exclusively for guests aged 16 or over.</li>
          <li>— Your private, secluded sundeck where you can enjoy every idle moment: soak up some sun, enjoy a book, a glass of wine or a sunset drink in complete privacy.</li>
          <li>— Spacious, fully-equipped pavilion.</li>
          <li>— After a long day relaxing or exploring the island, sink into your plush king-size bed with soft cotton linens and plump pillows.</li>
          <li>— Luxury en-suite with stone fixtures and freestanding bath.</li>
          <li>— CD and DVD player, flat-screen TV with complimentary movies on demand and iPod connectivity so you can enjoy your music, your way.</li>
        </ul>

        <div class="mt-7 text-sm text-slate-600">
          <p class="font-semibold">qualia Leeward Garden View Accessible Pavilion</p>
          <p class="mt-3">
            <span class="font-semibold">Garden View Accessible Pavilion from $1720 per night</span><br />
            qualia offers a Leeward Garden View Accessible Pavilion that has been configured for easy wheelchair access.
          </p>
        </div>

        <div class="h-10"></div>
      </div>

      <!-- Footer -->
      <div class="px-6 py-4 flex justify-end border-t border-slate-200">
        <button id="closeBtn" class="h-10 px-6 bg-[#8d8a84] text-white font-semibold hover:bg-[#7c7973] transition">
          Close
        </button>
      </div>

    </div>
  </div>
<!-- Room Details Modal end -->
  <script>
    // Source - https://stackoverflow.com/a/35038669
// Posted by resu, modified by community. See post 'Timeline' for change history
// Retrieved 2026-02-18, License - CC BY-SA 4.0

navigation.addEventListener("navigate", e => {
   const url = new URL(e.destination.url);
    console.log(url.pathname); 
    if(url.pathnam !== '/room-details') {   
            localStorage.removeItem('activeTab');
        }  
});


    //tabs bar
function openTab(evt, tabName, roomId = null) {
    localStorage.setItem('activeTab', tabName);
  // 1. Hide all tab contents
  const tabcontent = document.getElementsByClassName("tabcontent");
  for (let i = 0; i < tabcontent.length; i++) {
    tabcontent[i].classList.add("hidden");
  }

  // 2. Remove active state from all tab links
  const tablinks = document.getElementsByClassName("step-link");
  for (let i = 0; i < tablinks.length; i++) {
    tablinks[i].classList.remove("step-active");
  }

  // 3. Show selected tab
  document.getElementById(tabName).classList.remove("hidden");


  

  // 4. Activate clicked tab
//   evt.currentTarget.classList.add("step-active");
   document.querySelector(`.${evt}`).classList.add("step-active");

   // 5. If second tab, update URL
  if (tabName === "tab2" && roomId) {

      let baseUrl = window.location.origin + window.location.pathname;
      let newUrl = `${baseUrl}?room_id=${roomId}&tab=2`;

      window.history.pushState({}, '', newUrl);
  }
}

if(localStorage.getItem('activeTab')) {
    console.log('if')
    openTab(localStorage.getItem('activeTab'), localStorage.getItem('activeTab'))
} else {
    openTab('tab1', 'tab1') // default open
}

    const overlay = document.getElementById('overlay');
    const openBtn = document.getElementById('openBtn');
    const closeBtn = document.getElementById('closeBtn');
    const closeX = document.getElementById('closeX');

    const openModal = () => overlay.classList.remove('hidden');
    const closeModal = () => overlay.classList.add('hidden');

    if(openBtn) {
        openBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal();
        });
    }
    if(closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }
    if(closeX) {
        closeX.addEventListener('click', closeModal);
    }

    // close when clicking outside modal
   if(overlay) {
     overlay.addEventListener('click', (e) => {
      if (e.target === overlay) closeModal();
    });
   }

    // Rates dropdown toggle (smooth)
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-rates-toggle]');
      if (!btn) return;

      const card = btn.closest('article');
      if (!card) return;

      const panel = card.querySelector('[data-rates-panel]');
      if (!panel) return;

      const icon = btn.querySelector('[data-rates-icon]');
      const label = btn.querySelector('[data-rates-label]');

      const isOpen = panel.getAttribute('data-open') === 'true';

      const openPanel = () => {
        panel.setAttribute('data-open', 'true');
        panel.classList.remove('pointer-events-none');

        // start from 0 -> scrollHeight
        panel.style.height = '0px';
        panel.classList.remove('opacity-0');
        panel.classList.add('opacity-100');

        // force reflow
        panel.offsetHeight;

        panel.style.height = panel.scrollHeight + 'px';

        const onEnd = (ev) => {
          if (ev.propertyName !== 'height') return;
          panel.removeEventListener('transitionend', onEnd);
          if (panel.getAttribute('data-open') === 'true') {
            panel.style.height = 'auto';
          }
        };
        panel.addEventListener('transitionend', onEnd);
      };

      const closePanel = () => {
        panel.setAttribute('data-open', 'false');

        // from current (auto) -> fixed px -> 0
        panel.style.height = panel.scrollHeight + 'px';
        panel.offsetHeight;
        panel.style.height = '0px';

        panel.classList.remove('opacity-100');
        panel.classList.add('opacity-0');
        panel.classList.add('pointer-events-none');
      };

      if (isOpen) closePanel();
      else openPanel();

      btn.setAttribute('aria-expanded', String(!isOpen));
      if (icon) icon.textContent = isOpen ? '▾' : '▴';
      if (label) label.textContent = isOpen ? 'Show available rates' : 'Hide available rates';
    });
</script>

  
@endsection