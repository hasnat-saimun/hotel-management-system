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
  
  <!-- MAP SECTION -->
  <section class="py-10">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <div class="bg-white border border-slate-100 shadow-sm overflow-hidden">
        <div class="relative w-full h-[320px] md:h-[420px]">

          <!-- Google Map iframe (NO API KEY REQUIRED) -->
          <iframe
            class="absolute inset-0 w-full h-full border-0"
            loading="lazy"
            allowfullscreen
            referrerpolicy="no-referrer-when-downgrade"
            src="https://www.google.com/maps?q=Newark+Liberty+International+Airport&output=embed"
          ></iframe>

        </div>
      </div>

    </div>
  </section>
    <!-- CONTACT SECTION -->
  <section class="py-16 md:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

        <!-- LEFT INFO -->
        <div class="lg:col-span-4 space-y-10">

          <!-- Address -->
          <div class="flex gap-4">
            <div class="mt-1 text-amber-500">
              <!-- home icon -->
              <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 10.5L12 3l9 7.5"></path>
                <path d="M5 10v10h14V10"></path>
              </svg>
            </div>
            <div>
              <div class="font-semibold text-slate-900">California, United States</div>
              <div class="text-sm text-slate-400">Santa monica bullevard</div>
            </div>
          </div>

          <!-- Phone -->
          <div class="flex gap-4">
            <div class="mt-1 text-amber-500">
              <!-- phone icon -->
              <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 3 5.2 2 2 0 0 1 5 3h3a2 2 0 0 1 2 1.7c.1.8.3 1.6.6 2.3a2 2 0 0 1-.5 2.1L9 10.2a16 16 0 0 0 4.8 4.8l1.1-1.1a2 2 0 0 1 2.1-.5c.7.3 1.5.5 2.3.6a2 2 0 0 1 1.7 2Z"></path>
              </svg>
            </div>
            <div>
              <div class="font-semibold text-slate-900">00 (440) 9865 562</div>
              <div class="text-sm text-slate-400">Mon to Fri 9am to 6 pm</div>
            </div>
          </div>

          <!-- Email -->
          <div class="flex gap-4">
            <div class="mt-1 text-amber-500">
              <!-- mail icon -->
              <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16v16H4z"></path>
                <path d="m4 6 8 7 8-7"></path>
              </svg>
            </div>
            <div>
              <div class="font-semibold text-slate-900">support@colorlib.com</div>
              <div class="text-sm text-slate-400">Send us your query anytime!</div>
            </div>
          </div>

        </div>

        <!-- RIGHT FORM AREA -->
        <div class="lg:col-span-8">
          <form class="grid grid-cols-1 md:grid-cols-12 gap-6">

            <!-- Inputs column -->
            <div class="md:col-span-6 space-y-5">
              <input
                type="text"
                placeholder="Enter your name"
                class="w-full h-12 border border-slate-200 px-4 text-sm text-slate-700 placeholder:text-slate-400 outline-none focus:border-slate-400"
              />
              <input
                type="email"
                placeholder="Enter email address"
                class="w-full h-12 border border-slate-200 px-4 text-sm text-slate-700 placeholder:text-slate-400 outline-none focus:border-slate-400"
              />
              <input
                type="text"
                placeholder="Enter Subject"
                class="w-full h-12 border border-slate-200 px-4 text-sm text-slate-700 placeholder:text-slate-400 outline-none focus:border-slate-400"
              />
            </div>

            <!-- Message -->
            <div class="md:col-span-6">
              <textarea
                rows="8"
                placeholder="Enter Message"
                class="w-full border border-slate-200 px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 outline-none focus:border-slate-400 resize-none"
              ></textarea>
            </div>

            <!-- Button aligned right -->
            <div class="md:col-span-12 flex justify-end pt-2">
              <button
                type="submit"
                class="h-12 px-10 bg-amber-500 text-white font-extrabold text-sm hover:bg-amber-400 transition"
              >
                SEND MESSAGE
              </button>
            </div>

          </form>
        </div>

      </div>
    </div>
  </section>
@endsection