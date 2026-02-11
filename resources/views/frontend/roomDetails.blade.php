@extends('frontend.layout')

@section('content')


  <!-- STEPS BAR -->
  <section class=" bg-[#f9fbff] text-slate-700 py-10">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <div class="bg-[#d6cec3] px-10 py-4">
        <div class="flex items-center justify-between text-sm font-semibold text-white">

          <!-- Step 1 -->
          <div class="flex items-center gap-3 text-white/60">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#9c968c] text-xs font-bold">
              1
            </span>
            <span>Rooms and Rates</span>
          </div>

          <!-- Divider -->
          <div class="flex-1 mx-4 h-px bg-white/40"></div>

          <!-- Step 2 (Active) -->
          <div class="flex items-center gap-3 text-white">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-[#bfb6aa] text-xs font-bold">
              2
            </span>
            <span>Guest Details</span>
          </div>

          <!-- Divider -->
          <div class="flex-1 mx-4 h-px bg-white/40"></div>

          <!-- Step 3 -->
          <div class="flex items-center gap-3 text-white/60">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#9c968c] text-xs font-bold">
              3
            </span>
            <span>Confirmation</span>
          </div>

        </div>
      </div>

    </div>
  </section>

  <section class=" bg-[#f9fbff] text-slate-700 py-10">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        <!-- LEFT SIDEBAR -->
        <aside class="lg:col-span-3 space-y-6">

          <!-- Your Stay box -->
          <div class="bg-[#efe7dc] p-6">
            <h3 class="text-center text-2xl font-semibold text-slate-600">Your Stay</h3>

            <div class="mt-6 space-y-5 text-sm">
              <!-- Arrival -->
              <div>
                <label class="block text-slate-500 mb-2">Arrival Date</label>
                <div class="relative">
                  <input
                    type="text"
                    value="Sun, 15 Mar 2026"
                    class="w-full h-11 bg-white border border-slate-300 px-3 pr-10 text-slate-600 outline-none"
                    readonly
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
                    type="text"
                    value="Wed, 18 Mar 2026"
                    class="w-full h-11 bg-white border border-slate-300 px-3 pr-10 text-slate-600 outline-none"
                    readonly
                  />
                  <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500">
                    📅
                  </span>
                </div>
              </div>

              <!-- Nights -->
              <div>
                <label class="block text-slate-500 mb-2">Nights</label>
                <select class="w-full h-11 bg-white border border-slate-300 px-3 text-slate-600 outline-none">
                  <option selected>3</option>
                  <option>1</option>
                  <option>2</option>
                  <option>4</option>
                  <option>5</option>
                </select>
              </div>

              <!-- Adults -->
              <div>
                <label class="block text-slate-500 mb-2">
                  Adults <span class="text-xs text-slate-400">16+ years</span>
                </label>
                <select class="w-full h-11 bg-white border border-slate-300 px-3 text-slate-600 outline-none">
                  <option>1</option>
                  <option selected>2</option>
                  <option>3</option>
                  <option>4</option>
                </select>
              </div>

              <button
                class="w-full h-11 bg-[#8d8a84] text-white font-semibold hover:bg-[#7c7973] transition"
                type="button"
              >
                Check availability
              </button>
            </div>
          </div>

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
          <div class="flex justify-end text-sm text-slate-400 mb-3">
            <a href="#" class="hover:text-slate-600">Cancel an existing booking</a>
          </div>

          <!-- Available notice -->
          <div class="bg-[#7f7d73] text-white px-5 py-3 text-sm flex items-center gap-3">
            <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white/20">✓</span>
            <span>3 available rooms match your dates and search criteria</span>
          </div>

          <!-- Room cards -->
          <div class="mt-6 space-y-6">

            <!-- Card 1 -->
            <article class="bg-white border border-slate-200 p-5">
              <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <div class="md:col-span-3">
                  <img
                    class="w-full h-28 md:h-32 object-cover"
                    src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800&q=80"
                    alt="Room"
                  />
                </div>

                <div class="md:col-span-9">
                  <div class="flex items-start justify-between gap-6">
                    <h3 class="text-xl font-semibold text-slate-700">Leeward Pavilion</h3>
                    <div class="text-sm text-slate-500">
                      Total from <span class="font-semibold text-slate-700">AUD $6,840.00</span>
                    </div>
                  </div>

                  <p class="mt-3 text-sm leading-6 text-slate-500">
                    Elegantly designed and beautifully furnished, these one-bedroom pavilions have a private sundeck
                    with eucalypts framing stunning tropical garden or water views.
                  </p>

                  <div class="mt-4 flex flex-wrap items-center justify-between gap-4 text-sm">
                    <a href="#" class="underline text-slate-500 hover:text-slate-700">
                      Features, floor plan &amp; gallery
                    </a>

                    <div class="flex items-center gap-2 text-slate-500">
                      <span>Max guests</span>
                      <span>👥👥</span>
                    </div>

                    <div class="flex items-center gap-4">
                      <span class="text-[#b3564a] font-semibold">Hurry, only 1 left!</span>
                      <button class="h-10 px-5 bg-[#6d7a64] text-white text-sm font-semibold hover:bg-[#5f6b57] transition">
                        ▾ Show available rates
                      </button>
                    </div>
                  </div>

                </div>
              </div>
            </article>

            <!-- Card 2 -->
            <article class="bg-white border border-slate-200 p-5">
              <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <div class="md:col-span-3">
                  <img
                    class="w-full h-28 md:h-32 object-cover"
                    src="https://images.unsplash.com/photo-1505693314120-0d443867891c?auto=format&fit=crop&w=800&q=80"
                    alt="Room"
                  />
                </div>

                <div class="md:col-span-9">
                  <div class="flex items-start justify-between gap-6">
                    <h3 class="text-xl font-semibold text-slate-700">
                      Leeward Garden View Accessible Pavilion
                    </h3>
                    <div class="text-sm text-slate-500">
                      Total from <span class="font-semibold text-slate-700">AUD $6,840.00</span>
                    </div>
                  </div>

                  <p class="mt-3 text-sm leading-6 text-slate-500">
                    qualia offers a Leeward Garden View Accessible Pavilion, which has been configured for easy wheelchair access,
                    as well as providing all the classic Leeward Pavilion facilities.
                  </p>

                  <div class="mt-4 flex flex-wrap items-center justify-between gap-4 text-sm">
                    <a href="#" class="underline text-slate-500 hover:text-slate-700">
                      Features, floor plan &amp; gallery
                    </a>

                    <div class="flex items-center gap-2 text-slate-500">
                      <span>Max guests</span>
                      <span>👥👥</span>
                    </div>

                    <div class="flex items-center gap-4">
                      <span class="text-[#b3564a] font-semibold">Hurry, only 1 left!</span>
                      <button class="h-10 px-5 bg-[#6d7a64] text-white text-sm font-semibold hover:bg-[#5f6b57] transition">
                        ▾ Show available rates
                      </button>
                    </div>
                  </div>

                </div>
              </div>
            </article>

            <!-- Card 3 -->
            <article class="bg-white border border-slate-200 p-5">
              <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <div class="md:col-span-3">
                  <img
                    class="w-full h-28 md:h-32 object-cover"
                    src="https://images.unsplash.com/photo-1501117716987-c8e1ecb2106c?auto=format&fit=crop&w=800&q=80"
                    alt="Room"
                  />
                </div>

                <div class="md:col-span-9">
                  <div class="flex items-start justify-between gap-6">
                    <h3 class="text-xl font-semibold text-slate-700">Windward Pavilion</h3>
                    <div class="text-sm text-slate-500">
                      Total from <span class="font-semibold text-slate-700">AUD $9,870.00</span>
                    </div>
                  </div>

                  <p class="mt-3 text-sm leading-6 text-slate-500">
                    The Windward Pavilions boast a truly spectacular location, and private, infinity-edge plunge pools.
                  </p>

                  <div class="mt-4 flex flex-wrap items-center justify-between gap-4 text-sm">
                    <a href="#" class="underline text-slate-500 hover:text-slate-700">
                      Features, floor plan &amp; gallery
                    </a>

                    <div class="flex items-center gap-2 text-slate-500">
                      <span>Max guests</span>
                      <span>👥👥</span>
                    </div>

                    <div class="flex items-center gap-4">
                      <span class="text-[#b3564a] font-semibold">Hurry, only 3 left!</span>
                      <button class="h-10 px-5 bg-[#6d7a64] text-white text-sm font-semibold hover:bg-[#5f6b57] transition">
                        ▾ Show available rates
                      </button>
                    </div>
                  </div>

                </div>
              </div>
            </article>

          </div>

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
     <section class="bg-[#f9fbff] text-slate-700 py-10 ">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- LEFT SUMMARY SIDEBAR -->
        <aside class="lg:col-span-3 space-y-4">
          <div class="bg-[#eee6db] border border-slate-200">
            <div class="p-4 border-b border-slate-200 flex items-center justify-between">
              <div class="font-semibold text-slate-700">Your stay</div>
              <a href="#" class="text-xs underline text-slate-500 hover:text-slate-700">Modify</a>
            </div>

            <div class="p-4 text-xs space-y-4">
              <div>
                <div class="font-semibold text-slate-500">ARRIVAL</div>
                <div class="text-slate-600">Sunday, 15 Mar 2026</div>
              </div>

              <div>
                <div class="font-semibold text-slate-500">DEPARTURE</div>
                <div class="text-slate-600">Wednesday, 18 Mar 2026</div>
              </div>

              <div>
                <div class="font-semibold text-slate-500">NIGHTS</div>
                <div class="text-slate-600">3 nights</div>
              </div>

              <div>
                <div class="font-semibold text-slate-500">GUESTS</div>
                <div class="text-slate-600">2 adults</div>
              </div>

              <div>
                <div class="font-semibold text-slate-500">HOTEL</div>
                <div class="text-slate-600">qualia</div>
              </div>

              <div>
                <div class="font-semibold text-slate-500">ROOM</div>
                <div class="text-slate-600">Leeward Pavilion</div>
              </div>

              <div>
                <div class="font-semibold text-slate-500">RATE</div>
                <div class="text-slate-600">Classic Rate</div>
              </div>

              <div class="pt-4 border-t border-slate-200 space-y-3">
                <div>
                  <div class="font-semibold text-slate-500">TOTAL COST</div>
                  <div class="text-slate-700">AUD $6,840.00</div>
                </div>
                <div>
                  <div class="font-semibold text-slate-500">BALANCE DUE 7 DAYS PRIOR</div>
                  <div class="text-slate-700">AUD $5,472.00</div>
                </div>
              </div>

              <div class="bg-[#d9d0c3] px-3 py-4 -mx-4">
                <div class="font-semibold text-slate-600">DEPOSIT PAYABLE TODAY</div>
                <div class="text-lg font-semibold text-slate-700">AUD $1,368.00</div>
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

          <!-- Header -->
          <div class="flex items-start justify-between gap-6">
            <h1 class="text-2xl font-semibold text-slate-700">Reservation Information</h1>
            <div class="text-xs text-slate-400 mt-1">* Denotes a required field</div>
          </div>

          <!-- Guest Details -->
          <div class="mt-6">
            <h3 class="text-sm font-semibold text-slate-600 mb-3">Guest Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
              <select class="md:col-span-3 h-11 border bg-white  border-slate-300 px-3 text-sm">
                <option>Title *</option>
                <option>Mr</option>
                <option>Mrs</option>
                <option>Ms</option>
              </select>

              <input class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="First Name *" />
              <input class="md:col-span-5 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Last Name *" />

              <input class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Email Address *" />
              <input class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Confirm Email Address *" />
              <input class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Contact Number" />
            </div>
          </div>

          <!-- Address -->
          <div class="mt-8">
            <h3 class="text-sm font-semibold text-slate-600 mb-3">Address</h3>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
              <select class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm">
                <option>Australia</option>
                <option>Bangladesh</option>
                <option>United States</option>
              </select>

              <div class="hidden md:block md:col-span-8"></div>

              <input class="md:col-span-6 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Street Address Line 1 *" />
              <input class="md:col-span-6 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Street Address Line 2" />

              <input class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Suburb *" />

              <select class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm">
                <option>State *</option>
                <option>NSW</option>
                <option>VIC</option>
                <option>QLD</option>
              </select>

              <input class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Postcode *" />
            </div>

            <div class="mt-4 text-xs text-slate-500 font-semibold">
              Virgin Australia Velocity Frequent Flyer Number (optional)
            </div>

            <div class="mt-2 flex flex-col md:flex-row md:items-center gap-3">
              <input class="h-11 w-full md:w-64 border bg-white border-slate-300 px-3 text-sm" placeholder="10 Digit Membership Number" />
              <div class="text-xs text-slate-500 flex items-center gap-2">
                <span class="text-emerald-600">✓</span>
                <span>Earn 6,840 Velocity Frequent Flyer Points</span>
              </div>
            </div>
          </div>

          <!-- Additional Guest Details -->
          <div class="mt-10">
            <h3 class="text-sm font-semibold text-slate-600 mb-3">Additional Guest Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
              <select class="md:col-span-3 h-11 border bg-white border-slate-300 px-3 text-sm">
                <option>Title *</option>
                <option>Mr</option>
                <option>Mrs</option>
                <option>Ms</option>
              </select>

              <input class="md:col-span-4 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="First Name *" />
              <input class="md:col-span-5 h-11 border bg-white border-slate-300 px-3 text-sm" placeholder="Last Name *" />
            </div>
          </div>

          <!-- Dietary Requirements -->
          <div class="mt-12">
            <h2 class="text-2xl font-semibold text-slate-600">Dietary Requirements</h2>
            <p class="mt-2 text-xs text-slate-400">
              Do you have any dietary requirements we should be aware of?
            </p>

            <textarea
              rows="3"
              class="mt-4 w-full border bg-white border-slate-300 px-3 py-3 text-sm"
              placeholder="Enter your dietary requirements here. 1000 characters maximum."
            ></textarea>
          </div>

          <!-- Special Requests -->
          <div class="mt-12">
            <h2 class="text-2xl font-semibold text-slate-600">Special Requests</h2>
            <p class="mt-2 text-xs text-slate-400">
              Please let us know if you have any special requests for your pavilion, or if you are travelling for a special occasion.
            </p>

            <textarea
              rows="3"
              class="mt-4 w-full border bg-white border-slate-300 px-3 py-3 text-sm"
              placeholder="Enter your comments here. 1000 characters maximum."
            ></textarea>
          </div>

          <!-- Arrival and Departure Information -->
          <div class="mt-12">
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
          </div>

          <!-- Secure Payment Method -->
          <div class="mt-12">
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
          </div>

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

            <div class="mt-6 flex flex-col sm:flex-row sm:items-center gap-4">
              <button class="h-11 px-6 bg-[#6d7a64] text-white font-semibold hover:bg-[#5f6b57] transition">
                Submit Booking
              </button>
              <div class="text-sm text-slate-500 flex items-center gap-2">
                <span class="text-emerald-600">✓</span>
                <span>You won't find a better price, it’s our guarantee</span>
              </div>
            </div>
          </div>

        </main>

      </div>
    </div>
  </section>


  <!-- RATES BOX -->
  <section class="py-10">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

      <div class="border border-[#cfc6b7]">

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
                Total <span class="font-semibold">AUD $6,840.00</span>
                <span class="inline-flex h-4 w-4 items-center justify-center rounded-full bg-slate-200 text-[10px] text-slate-600 ml-1">i</span>
              </div>

              <button
                class="mt-4 inline-flex items-center justify-center gap-2 h-10 px-6 bg-[#6d7a64] text-white text-sm font-semibold hover:bg-[#5f6b57] transition"
              >
                <span>Book now</span>
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

      </div>

    </div>
  </section>

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


<!-- Room Details Modal str -->
   <!-- PAGE (dummy background) -->
  <div class="min-h-screen flex items-center justify-center p-6">
    <button id="openBtn" class="px-6 py-3 bg-[#6d7a64] text-white font-semibold">
      Open Modal
    </button>
  </div>

  <!-- OVERLAY -->
  <div id="overlay" class="fixed inset-0 bg-black/60 hidden items-center justify-center p-4">
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
    const overlay = document.getElementById('overlay');
    const openBtn = document.getElementById('openBtn');
    const closeBtn = document.getElementById('closeBtn');
    const closeX = document.getElementById('closeX');

    const openModal = () => overlay.classList.remove('hidden');
    const closeModal = () => overlay.classList.add('hidden');

    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    closeX.addEventListener('click', closeModal);

    // close when clicking outside modal
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) closeModal();
    });
  </script>

  
  @endsection