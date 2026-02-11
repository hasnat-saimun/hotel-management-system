@extends('frontend.layout')

@section('content')
     <!-- PAGE HEADER / BREADCRUMB -->
  <section class="relative h-[280px] md:h-[320px] flex items-center justify-center">
    
    <!-- Background image (online, free source) -->
    <div
      class="absolute inset-0 bg-center bg-cover"
      style="background-image:url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=2000&q=80');"
    ></div>

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- Content -->
    <div class="relative text-center text-white">
      <h1 class="text-3xl md:text-4xl font-extrabold">
        About Us
      </h1>

      <div class="mt-3 text-sm text-white/80">
        <a href="#" class="hover:text-white transition">Home</a>
        <span class="mx-2">→</span>
        <span>About</span>
      </div>
    </div>
  </section>

  
  <!-- LEFT ALIGNED SECTION -->
  <section class="py-20 md:py-24">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <!-- Title -->
      <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-10">
        Left Aligned
      </h2>

      <!-- Content -->
      <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start">

        <!-- Image (Left) -->
        <div class="md:col-span-3">
          <img
            src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=500&q=80"
            alt="Left aligned image"
            class="w-full object-cover"
          />
        </div>

        <!-- Text (Right) -->
        <div class="md:col-span-9 text-sm leading-7 text-slate-500">
          <p>
            Recently, the US Federal government banned online casinos from operating in America by
            making it illegal to transfer money to them through any US bank or payment system. As a
            result of this law, most of the popular online casino networks such as Party Gaming and
            PlayTech left the United States. Overnight, online casino players found themselves being
            chased by the Federal government. But, after a fortnight, the online casino industry came
            up with a solution and new online casinos started taking root.
          </p>

          <p class="mt-4">
            These began to operate under a different business umbrella, and by doing that, rendered
            the transfer of money to and from them legal. A major part of this was enlisting electronic
            banking systems that would accept this new clarification and start doing business with
            me. Listed in this article are the electronic banking systems that accept players from the
            United States that wish to play in online casinos.
          </p>
        </div>

      </div>
    </div>
  </section>
 
  <!-- TABLE SECTION -->
  <section class="bg-[#f9fbff] text-slate-700 py-16 md:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <!-- Title -->
      <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-8">
        Table
      </h2>

      <!-- Card wrapper -->
      <div class="bg-white shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-white">
              <tr class="border-b border-slate-100">
                <th class="px-10 py-5 text-left text-xs font-extrabold tracking-wider text-slate-900">#</th>
                <th class="px-10 py-5 text-left text-xs font-extrabold tracking-wider text-slate-900">COUNTRIES</th>
                <th class="px-10 py-5 text-left text-xs font-extrabold tracking-wider text-slate-900">VISITS</th>
                <th class="px-10 py-5 text-left text-xs font-extrabold tracking-wider text-slate-900">PERCENTAGES</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
              <!-- Row 1 -->
              <tr class="bg-white">
                <td class="px-10 py-6 text-sm text-slate-400">01</td>
                <td class="px-10 py-6">
                  <div class="flex items-center gap-5">
                    <img
                      class="h-7 w-11 object-cover"
                      src="https://flagcdn.com/w80/ca.png"
                      alt="Canada flag"
                    />
                    <span class="text-sm text-slate-500">Canada</span>
                  </div>
                </td>
                <td class="px-10 py-6 text-sm text-slate-400">645032</td>
                <td class="px-10 py-6">
                  <div class="h-1.5 w-72 bg-slate-100">
                    <div class="h-1.5 w-[78%] bg-blue-500"></div>
                  </div>
                </td>
              </tr>

              <!-- Row 2 -->
              <tr class="bg-white">
                <td class="px-10 py-6 text-sm text-slate-400">02</td>
                <td class="px-10 py-6">
                  <div class="flex items-center gap-5">
                    <img
                      class="h-7 w-11 object-cover"
                      src="https://flagcdn.com/w80/us.png"
                      alt="USA flag"
                    />
                    <span class="text-sm text-slate-500">Canada</span>
                  </div>
                </td>
                <td class="px-10 py-6 text-sm text-slate-400">645032</td>
                <td class="px-10 py-6">
                  <div class="h-1.5 w-72 bg-slate-100">
                    <div class="h-1.5 w-[28%] bg-pink-500"></div>
                  </div>
                </td>
              </tr>

              <!-- Row 3 -->
              <tr class="bg-white">
                <td class="px-10 py-6 text-sm text-slate-400">03</td>
                <td class="px-10 py-6">
                  <div class="flex items-center gap-5">
                    <img
                      class="h-7 w-11 object-cover"
                      src="https://flagcdn.com/w80/gb.png"
                      alt="UK flag"
                    />
                    <span class="text-sm text-slate-500">Canada</span>
                  </div>
                </td>
                <td class="px-10 py-6 text-sm text-slate-400">645032</td>
                <td class="px-10 py-6">
                  <div class="h-1.5 w-72 bg-slate-100">
                    <div class="h-1.5 w-[52%] bg-orange-500"></div>
                  </div>
                </td>
              </tr>

              <!-- Row 4 -->
              <tr class="bg-white">
                <td class="px-10 py-6 text-sm text-slate-400">04</td>
                <td class="px-10 py-6">
                  <div class="flex items-center gap-5">
                    <img
                      class="h-7 w-11 object-cover"
                      src="https://flagcdn.com/w80/de.png"
                      alt="Germany flag"
                    />
                    <span class="text-sm text-slate-500">Canada</span>
                  </div>
                </td>
                <td class="px-10 py-6 text-sm text-slate-400">645032</td>
                <td class="px-10 py-6">
                  <div class="h-1.5 w-72 bg-slate-100">
                    <div class="h-1.5 w-[60%] bg-emerald-400"></div>
                  </div>
                </td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
   <!-- GALLERY SECTION -->
  <section class="py-20 md:py-24">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <!-- Heading -->
      <div class="text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">
          Royal Hotel Gallery
        </h2>
        <p class="mt-3 text-sm text-slate-400">
          Who are in extremely love with eco friendly system.
        </p>
      </div>

      <!-- Images row (3 columns like screenshot) -->
      <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Image 1 -->
        <div class="overflow-hidden bg-slate-100">
          <img
            src="https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=1400&q=80"
            alt="Room"
            class="h-64 md:h-72 w-full object-cover"
          />
        </div>

        <!-- Image 2 -->
        <div class="overflow-hidden bg-slate-100">
          <img
            src="https://images.unsplash.com/photo-1529692236671-f1f6cf9683ba?auto=format&fit=crop&w=1400&q=80"
            alt="Chef"
            class="h-64 md:h-72 w-full object-cover"
          />
        </div>

        <!-- Image 3 -->
        <div class="overflow-hidden bg-slate-100">
          <img
            src="https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1400&q=80"
            alt="Pool"
            class="h-64 md:h-72 w-full object-cover"
          />
        </div>
      </div>

    </div>
  </section>

   <!-- TYPOGRAPHY + LISTS SECTION -->
  <section class="bg-[#f9fbff] text-slate-700 py-16 md:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <div class="grid grid-cols-1 md:grid-cols-3 gap-14">

        <!-- Typography -->
        <div>
          <h3 class="text-xl font-extrabold text-slate-900">Typography</h3>

          <div class="mt-8 space-y-5">
            <div class="text-5xl font-extrabold text-slate-400">This is header 01</div>
            <div class="text-4xl font-extrabold text-slate-400">This is header 02</div>
            <div class="text-3xl font-extrabold text-slate-400">This is header 03</div>
            <div class="text-2xl font-extrabold text-slate-400">This is header 04</div>
            <div class="text-xl font-extrabold text-slate-400">This is header 01</div>
            <div class="text-base font-extrabold text-slate-400">This is header 01</div>
          </div>
        </div>

        <!-- Unordered List -->
        <div>
          <h3 class="text-xl font-extrabold text-slate-900">Unordered List</h3>

          <ul class="mt-8 space-y-3 text-sm text-slate-400">
            <li class="flex gap-3">
              <span class="mt-1.5 h-2.5 w-2.5 rounded-full border-2 border-amber-500"></span>
              <span>Fta Keys</span>
            </li>
            <li class="flex gap-3">
              <span class="mt-1.5 h-2.5 w-2.5 rounded-full border-2 border-amber-500"></span>
              <span>For Women Only Your Computer Usage</span>
            </li>
            <li class="flex gap-3">
              <span class="mt-1.5 h-2.5 w-2.5 rounded-full border-2 border-amber-500"></span>
              <span>Facts Why Inkjet Printing Is Very Appealing</span>
            </li>

            <!-- nested -->
            <li class="ml-10">
              <ul class="space-y-2">
                <li class="flex gap-3">
                  <span class="mt-1.5 h-2.5 w-2.5 rounded-full border-2 border-amber-500"></span>
                  <span>Addiction When Gambling Becomes</span>
                </li>
                <li class="ml-10">
                  <ul class="space-y-2 list-disc">
                    <li class="flex gap-3">
                      <span class="mt-1.5 h-2.5 w-2.5 rounded-full border-2 border-amber-500"></span>
                      <span>Protective Preventative</span>
                    </li>
                    <li class="ml-10 list-none text-slate-400">Maintenance</li>
                  </ul>
                </li>
              </ul>
            </li>

            <li class="flex gap-3 pt-2">
              <span class="mt-1.5 h-2.5 w-2.5 rounded-full border-2 border-amber-500"></span>
              <span>Dealing With Technical Support 10 Useful Tips</span>
            </li>
            <li class="flex gap-3">
              <span class="mt-1.5 h-2.5 w-2.5 rounded-full border-2 border-amber-500"></span>
              <span>Make Myspace Your Best Designed Space</span>
            </li>
            <li class="flex gap-3">
              <span class="mt-1.5 h-2.5 w-2.5 rounded-full border-2 border-amber-500"></span>
              <span>Cleaning And Organizing Your Computer</span>
            </li>
          </ul>
        </div>

        <!-- Ordered List -->
        <div>
          <h3 class="text-xl font-extrabold text-slate-900">Ordered List</h3>

          <ol class="mt-8 space-y-3 text-sm text-slate-400">
            <li class="flex gap-3">
              <span class="text-amber-500 font-extrabold">01.</span>
              <span>Fta Keys</span>
            </li>
            <li class="flex gap-3">
              <span class="text-amber-500 font-extrabold">02.</span>
              <span>For Women Only Your Computer Usage</span>
            </li>
            <li class="flex gap-3">
              <span class="text-amber-500 font-extrabold">03.</span>
              <span>Facts Why Inkjet Printing Is Very Appealing</span>
            </li>

            <!-- nested ordered -->
            <li class="ml-10">
              <div class="flex gap-3">
                <span class="text-amber-500 font-extrabold">a.</span>
                <span>Addiction When Gambling Becomes</span>
              </div>

              <div class="ml-12 mt-2 space-y-2">
                <div class="flex gap-3">
                  <span class="text-amber-500 font-extrabold">i.</span>
                  <span>Protective Preventative</span>
                </div>
                <div class="ml-7 text-slate-400">Maintenance</div>
              </div>
            </li>

            <li class="flex gap-3 pt-2">
              <span class="text-amber-500 font-extrabold">04.</span>
              <span>Dealing With Technical Support 10 Useful Tips</span>
            </li>
            <li class="flex gap-3">
              <span class="text-amber-500 font-extrabold">05.</span>
              <span>Make Myspace Your Best Designed Space</span>
            </li>
            <li class="flex gap-3">
              <span class="text-amber-500 font-extrabold">06.</span>
              <span>Cleaning And Organizing Your Computer</span>
            </li>
          </ol>
        </div>

      </div>

      <!-- Divider line like screenshot -->
      <div class="mt-16 border-t border-slate-200"></div>

    </div>
  </section>

  
  <!-- FORM ELEMENT SECTION -->
  <section class="bg-[#f9fbff] text-slate-700 py-16 md:py-20">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

      <!-- Title -->
      <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-10">
        Form Element
      </h2>

      <!-- Form -->
      <form class="space-y-6">

        <!-- First Name -->
        <input
          type="text"
          placeholder="First Name"
          class="w-full h-12 rounded-sm bg-white px-4 text-sm text-slate-600 placeholder:text-slate-400 border border-transparent focus:border-slate-300 focus:outline-none"
        />

        <!-- Last Name -->
        <input
          type="text"
          placeholder="Last Name"
          class="w-full h-12 rounded-sm bg-white px-4 text-sm text-slate-600 placeholder:text-slate-400 border border-transparent focus:border-slate-300 focus:outline-none"
        />

        <!-- Last Name (duplicate as in screenshot) -->
        <input
          type="text"
          placeholder="Last Name"
          class="w-full h-12 rounded-sm bg-white px-4 text-sm text-slate-600 placeholder:text-slate-400 border border-transparent focus:border-slate-300 focus:outline-none"
        />

        <!-- Email -->
        <input
          type="email"
          placeholder="Email address"
          class="w-full h-12 rounded-sm bg-white px-4 text-sm text-slate-600 placeholder:text-slate-400 border border-transparent focus:border-slate-300 focus:outline-none"
        />

        <!-- Address (with icon) -->
        <div class="relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
            📍
          </span>
          <input
            type="text"
            placeholder="Address"
            class="w-full h-12 rounded-sm bg-white pl-10 pr-4 text-sm text-slate-600 placeholder:text-slate-400 border border-transparent focus:border-slate-300 focus:outline-none"
          />
        </div>

        <!-- City -->
        <div class="relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
            ✈️
          </span>
          <select
            class="w-full h-12 rounded-sm bg-white pl-10 pr-10 text-sm text-slate-600 border border-transparent focus:border-slate-300 focus:outline-none appearance-none"
          >
            <option>City</option>
            <option>New York</option>
            <option>London</option>
            <option>Tokyo</option>
          </select>
          <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
            ▾
          </span>
        </div>

        <!-- Country -->
        <div class="relative">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
            🌍
          </span>
          <select
            class="w-full h-12 rounded-sm bg-white pl-10 pr-10 text-sm text-slate-600 border border-transparent focus:border-slate-300 focus:outline-none appearance-none"
          >
            <option>Country</option>
            <option>USA</option>
            <option>Canada</option>
            <option>UK</option>
          </select>
          <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
            ▾
          </span>
        </div>

        <!-- Message -->
        <textarea
          rows="4"
          placeholder="Message"
          class="w-full rounded-sm bg-white px-4 py-3 text-sm text-slate-600 placeholder:text-slate-400 border border-transparent focus:border-slate-300 focus:outline-none resize-none"
        ></textarea>

      </form>

    </div>
  </section>
 <!-- SWITCHES BLOCK (matched to your screenshot) -->
  <section class="bg-[#f9fbff] text-slate-700 py-10">
    <div class="mx-auto max-w-xl px-6">

      <h3 class="text-3xl font-extrabold text-slate-900">Switches</h3>

      <div class="mt-8 space-y-6 text-sm text-slate-500">

        <!-- 01 -->
        <div class="flex items-center justify-between">
          <div class="flex gap-3">
            <span class="text-slate-400">01.</span>
            <span>Sample Switch</span>
          </div>

          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" class="sr-only peer" />
            <div class="w-11 h-6 bg-slate-200 rounded-full transition peer-checked:bg-slate-200"></div>
            <div class="absolute left-1 top-1 h-4 w-4 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
          </label>
        </div>

        <!-- 02 -->
        <div class="flex items-center justify-between">
          <div class="flex gap-3">
            <span class="text-slate-400">02.</span>
            <span>Primary Color Switch</span>
          </div>

          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" class="sr-only peer" checked />
            <div class="w-11 h-6 bg-slate-200 rounded-full transition peer-checked:bg-amber-400"></div>
            <div class="absolute left-1 top-1 h-4 w-4 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
          </label>
        </div>

        <!-- 03 -->
        <div class="flex items-center justify-between">
          <div class="flex gap-3">
            <span class="text-slate-400">03.</span>
            <span>Confirm Color Switch</span>
          </div>

          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" class="sr-only peer" checked />
            <div class="w-11 h-6 bg-slate-200 rounded-full transition peer-checked:bg-cyan-400"></div>
            <div class="absolute left-1 top-1 h-4 w-4 bg-white rounded-full shadow transition peer-checked:translate-x-5"></div>
          </label>
        </div>

      </div>

    </div>
  </section>
  
  <section class="bg-[#f9fbff] text-slate-600 py-12">
    <div class="mx-auto max-w-sm px-6">

      <!-- Selectboxes -->
      <h3 class="text-3xl font-extrabold text-slate-900 mb-6">
        Selectboxes
      </h3>

      <div class="relative w-40 mb-14">
        <select
          class="w-full h-12 bg-white px-4 pr-10 text-sm text-slate-600 border border-transparent focus:border-slate-300 focus:outline-none appearance-none"
        >
          <option>English</option>
          <option>Bangla</option>
          <option>Hindi</option>
        </select>

        <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
          ▾
        </span>
      </div>

      <!-- Checkboxes -->
      <h3 class="text-3xl font-extrabold text-slate-900 mb-8">
        Checkboxes
      </h3>

      <div class="space-y-5 text-sm">

        <!-- 01 -->
        <div class="flex items-center justify-between">
          <div class="flex gap-3">
            <span class="text-slate-400">01.</span>
            <span>Sample Checkbox</span>
          </div>
          <input type="checkbox" class="h-4 w-4 border-slate-300 rounded" />
        </div>

        <!-- 02 -->
        <div class="flex items-center justify-between">
          <div class="flex gap-3">
            <span class="text-slate-400">02.</span>
            <span>Primary Color Checkbox</span>
          </div>
          <input
            type="checkbox"
            checked
            class="h-4 w-4 rounded border-slate-300 accent-pink-500"
          />
        </div>

        <!-- 03 -->
        <div class="flex items-center justify-between">
          <div class="flex gap-3">
            <span class="text-slate-400">03.</span>
            <span>Confirm Color Checkbox</span>
          </div>
          <input type="checkbox" class="h-4 w-4 border-slate-300 rounded" />
        </div>

        <!-- 04 -->
        <div class="flex items-center justify-between opacity-50">
          <div class="flex gap-3">
            <span class="text-slate-400">04.</span>
            <span>Disabled Checkbox</span>
          </div>
          <input
            type="checkbox"
            disabled
            class="h-4 w-4 border-slate-300 rounded"
          />
        </div>

        <!-- 05 -->
        <div class="flex items-center justify-between opacity-50">
          <div class="flex gap-3">
            <span class="text-slate-400">05.</span>
            <span>Disabled Checkbox active</span>
          </div>
          <input
            type="checkbox"
            checked
            disabled
            class="h-4 w-4 border-slate-300 rounded"
          />
        </div>

      </div>

    </div>
  </section>
  
  <!-- RADIOS (matched to screenshot) -->
  <section class="bg-[#f9fbff] text-slate-600 py-12">
    <div class="mx-auto max-w-sm px-6">

      <h3 class="text-3xl font-extrabold text-slate-900 mb-8">
        Radios
      </h3>

      <div class="space-y-5 text-sm">

        <!-- 01 -->
        <label class="flex items-center justify-between cursor-pointer">
          <div class="flex gap-3">
            <span class="text-slate-400">01.</span>
            <span>Sample radio</span>
          </div>
          <input
            type="radio"
            name="radios"
            class="h-4 w-4 border-slate-300 text-slate-900 focus:ring-0"
          />
        </label>

        <!-- 02 -->
        <label class="flex items-center justify-between cursor-pointer">
          <div class="flex gap-3">
            <span class="text-slate-400">02.</span>
            <span>Primary Color radio</span>
          </div>
          <input
            type="radio"
            name="radios"
            class="h-4 w-4 border-slate-300 text-pink-500 focus:ring-0"
          />
        </label>

        <!-- 03 (active like screenshot) -->
        <label class="flex items-center justify-between cursor-pointer">
          <div class="flex gap-3">
            <span class="text-slate-400">03.</span>
            <span>Confirm Color radio</span>
          </div>
          <input
            type="radio"
            name="radios"
            checked
            class="h-4 w-4 border-slate-300 text-slate-900 focus:ring-0"
          />
        </label>

        <!-- 04 disabled -->
        <label class="flex items-center justify-between opacity-50">
          <div class="flex gap-3">
            <span class="text-slate-400">04.</span>
            <span>Disabled radio</span>
          </div>
          <input
            type="radio"
            name="radios_disabled"
            disabled
            class="h-4 w-4 border-slate-300 text-slate-900 focus:ring-0"
          />
        </label>

        <!-- 05 disabled active -->
        <label class="flex items-center justify-between opacity-50">
          <div class="flex gap-3">
            <span class="text-slate-400">05.</span>
            <span>Disabled radio active</span>
          </div>
          <input
            type="radio"
            name="radios_disabled"
            checked
            disabled
            class="h-4 w-4 border-slate-300 text-slate-900 focus:ring-0"
          />
        </label>

      </div>

    </div>
  </section>
  @endsection