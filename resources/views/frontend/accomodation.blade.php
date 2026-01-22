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
        Accomodation 
      </h1>

      <div class="mt-3 text-sm text-white/80">
        <a href="#" class="hover:text-white transition">Home</a>
        <span class="mx-2">→</span>
        <span>Accomodation</span>
      </div>
    </div>
  </section>
  

    <!-- Hotel Accommodation Section -->
  <section class="py-16 md:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

      <!-- Heading -->
      <div class="text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold">
          Hotel Accomodation
        </h2>
        <p class="mt-3 text-sm md:text-base text-slate-500">
          We all live in an age that belongs to the young at heart. Life that is becoming extremely fast,
        </p>
      </div>

      <!-- Cards -->
      <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

        <!-- Card 1 -->
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

        <!-- Card 2 -->
        <div class="text-center group">
          <div class="relative overflow-hidden rounded-2xl bg-slate-100 shadow-sm">
            <img
              src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80"
              alt="Single Deluxe Room"
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
          <h3 class="mt-6 text-base font-extrabold">Single Deluxe Room</h3>
          <div class="mt-2">
            <span class="text-sky-500 text-2xl font-extrabold">$200</span>
            <span class="text-sky-300 text-sm font-semibold">/night</span>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="text-center group">
          <div class="relative overflow-hidden rounded-2xl bg-slate-100 shadow-sm">
            <img
              src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80"
              alt="Honeymoon Suit"
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
          <h3 class="mt-6 text-base font-extrabold">Honeymoon Suit</h3>
          <div class="mt-2">
            <span class="text-sky-500 text-2xl font-extrabold">$750</span>
            <span class="text-sky-300 text-sm font-semibold">/night</span>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="text-center group">
          <div class="relative overflow-hidden rounded-2xl bg-slate-100 shadow-sm">
            <img
              src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80"
              alt="Economy Double"
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
          <h3 class="mt-6 text-base font-extrabold">Economy Double</h3>
          <div class="mt-2">
            <span class="text-sky-500 text-2xl font-extrabold">$200</span>
            <span class="text-sky-300 text-sm font-semibold">/night</span>
          </div>
        </div>

      </div>
    <div class="mx-auto max-w-6xl px-4">

      <div class="bg-gradient-to-r from-[#020c1b] to-[#04132d] px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">

          <!-- LEFT TITLE -->
          <div class="md:col-span-3">
            <h3 class="text-white text-xl font-extrabold leading-tight">
              BOOK<br />
              YOUR ROOM
            </h3>
          </div>

          <!-- RIGHT FORM -->
          <div class="md:col-span-9">
            <form class="grid grid-cols-12 gap-4">

              <!-- Row 1 -->
              <div class="col-span-12 md:col-span-4">
                <input
                  type="date"
                  class="w-full h-11 bg-transparent border border-white/20 px-3 text-sm text-white outline-none [color-scheme:dark]"
                  placeholder="Arrival Date"
                />
              </div>

              <div class="col-span-6 md:col-span-4">
                <select class="w-full h-11 bg-transparent border border-white/20 px-3 text-sm text-white outline-none">
                  <option class="bg-[#04132d]">Adult</option>
                  <option class="bg-[#04132d]">1</option>
                  <option class="bg-[#04132d]">2</option>
                  <option class="bg-[#04132d]">3</option>
                </select>
              </div>

              <div class="col-span-6 md:col-span-4">
                <select class="w-full h-11 bg-transparent border border-white/20 px-3 text-sm text-white outline-none">
                  <option class="bg-[#04132d]">Child</option>
                  <option class="bg-[#04132d]">0</option>
                  <option class="bg-[#04132d]">1</option>
                  <option class="bg-[#04132d]">2</option>
                </select>
              </div>

              <!-- Row 2 -->
              <div class="col-span-12 md:col-span-4">
                <input
                  type="date"
                  class="w-full h-11 bg-transparent border border-white/20 px-3 text-sm text-white outline-none [color-scheme:dark]"
                  placeholder="Departure Date"
                />
              </div>

              <div class="col-span-6 md:col-span-4">
                <select class="w-full h-11 bg-transparent border border-white/20 px-3 text-sm text-white outline-none">
                  <option class="bg-[#04132d]">Child</option>
                  <option class="bg-[#04132d]">0</option>
                  <option class="bg-[#04132d]">1</option>
                  <option class="bg-[#04132d]">2</option>
                </select>
              </div>

              <div class="col-span-6 md:col-span-4">
                <button
                  type="submit"
                  class="w-full h-11 bg-yellow-500 text-black font-extrabold text-sm hover:bg-yellow-400 transition"
                >
                  BOOK NOW
                </button>
              </div>

            </form>
          </div>

        </div>
      </div>

    </div>

    
      <!-- Heading -->
      <div class="text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold">
          Hotel Accomodation
        </h2>
        <p class="mt-3 text-sm md:text-base text-slate-500">
          We all live in an age that belongs to the young at heart. Life that is becoming extremely fast,
        </p>
      </div>
      
      <!-- Cards -->
      <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

        <!-- Card 1 -->
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

        <!-- Card 2 -->
        <div class="text-center group">
          <div class="relative overflow-hidden rounded-2xl bg-slate-100 shadow-sm">
            <img
              src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80"
              alt="Single Deluxe Room"
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
          <h3 class="mt-6 text-base font-extrabold">Single Deluxe Room</h3>
          <div class="mt-2">
            <span class="text-sky-500 text-2xl font-extrabold">$200</span>
            <span class="text-sky-300 text-sm font-semibold">/night</span>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="text-center group">
          <div class="relative overflow-hidden rounded-2xl bg-slate-100 shadow-sm">
            <img
              src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80"
              alt="Honeymoon Suit"
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
          <h3 class="mt-6 text-base font-extrabold">Honeymoon Suit</h3>
          <div class="mt-2">
            <span class="text-sky-500 text-2xl font-extrabold">$750</span>
            <span class="text-sky-300 text-sm font-semibold">/night</span>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="text-center group">
          <div class="relative overflow-hidden rounded-2xl bg-slate-100 shadow-sm">
            <img
              src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80"
              alt="Economy Double"
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
          <h3 class="mt-6 text-base font-extrabold">Economy Double</h3>
          <div class="mt-2">
            <span class="text-sky-500 text-2xl font-extrabold">$200</span>
            <span class="text-sky-300 text-sm font-semibold">/night</span>
          </div>
        </div>

      </div>
      <!-- Cards -->
      <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

        <!-- Card 1 -->
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

        <!-- Card 2 -->
        <div class="text-center group">
          <div class="relative overflow-hidden rounded-2xl bg-slate-100 shadow-sm">
            <img
              src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80"
              alt="Single Deluxe Room"
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
          <h3 class="mt-6 text-base font-extrabold">Single Deluxe Room</h3>
          <div class="mt-2">
            <span class="text-sky-500 text-2xl font-extrabold">$200</span>
            <span class="text-sky-300 text-sm font-semibold">/night</span>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="text-center group">
          <div class="relative overflow-hidden rounded-2xl bg-slate-100 shadow-sm">
            <img
              src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80"
              alt="Honeymoon Suit"
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
          <h3 class="mt-6 text-base font-extrabold">Honeymoon Suit</h3>
          <div class="mt-2">
            <span class="text-sky-500 text-2xl font-extrabold">$750</span>
            <span class="text-sky-300 text-sm font-semibold">/night</span>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="text-center group">
          <div class="relative overflow-hidden rounded-2xl bg-slate-100 shadow-sm">
            <img
              src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1200&q=80"
              alt="Economy Double"
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
          <h3 class="mt-6 text-base font-extrabold">Economy Double</h3>
          <div class="mt-2">
            <span class="text-sky-500 text-2xl font-extrabold">$200</span>
            <span class="text-sky-300 text-sm font-semibold">/night</span>
          </div>
        </div>

      </div>
  </section>

  

@endsection