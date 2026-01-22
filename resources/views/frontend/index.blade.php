@extends('frontend.layout')

@section('content')


    <!-- HERO -->
    <section class="relative">
      <!-- Replace HERO_IMAGE_URL with your hosted image link to match exactly -->
      <!-- Example: https://yourdomain.com/assets/hero.png -->
      <div
        class="relative min-h-[78vh] md:min-h-[82vh] bg-center bg-cover"
        style="
          background-image: url('https://picsum.photos/1200/600');
        "
      >
        <!-- Overlay (like screenshot) -->
        <div class="absolute inset-0 bg-black/40"></div>

        <!-- Center content -->
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex min-h-[78vh] md:min-h-[82vh] items-center justify-center">
            <div class="text-center text-white max-w-3xl">
              <p class="text-xs md:text-sm tracking-[0.25em] uppercase opacity-90">
                AWAY FROM MONOTONOUS LIFE
              </p>

              <h1 class="mt-4 text-4xl md:text-6xl font-extrabold leading-tight">
                Relax Your Mind
              </h1>

              <p class="mt-4 text-sm md:text-base text-white/85">
                If you are looking at blank cassettes on the web, you may be very confused at the
                difference in price. You may see some for as low as $17 each.
              </p>

              <div class="mt-8">
                <a
                  href="#"
                  class="inline-flex items-center justify-center rounded bg-amber-500 px-8 py-3 text-sm font-bold text-slate-900 hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-300"
                >
                  GET STARTED
                </a>
              </div>
            </div>
          </div>
        </div>

      <!-- BOOKING BAR -->
        <div class=" inset-x-0 bottom-0">
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
    </div>
  </section>
    <!-- Royal Facilities Section -->
  <section class="relative py-16 md:py-20 overflow-hidden">
    <!-- Background image (ONLINE) -->
    <div
      class="absolute inset-0 bg-center bg-cover"
      style="background-image:url('https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=2200&q=80');"
    ></div>

    <!-- Dark overlay like screenshot -->
    <div class="absolute inset-0 bg-[#0b0f1f]/80"></div>

    <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
      <!-- Heading -->
      <div class="text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">
          Royal Facilities
        </h2>
        <p class="mt-2 text-sm text-white/60">
          Who are in extremely love with eco friendly system.
        </p>
      </div>

      <!-- Cards Grid (2 rows x 3 cols like image) -->
      <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card -->
        <div class="rounded-xl border border-white/20 bg-white/5 p-6 shadow-[0_0_0_1px_rgba(255,255,255,0.05)] backdrop-blur">
          <div class="flex items-start gap-3">
            <div class="mt-0.5 text-amber-400">
              <!-- simple icon -->
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 3h16M6 7h12M7 7v14m10-14v14M5 21h14"/>
              </svg>
            </div>
            <div>
              <h3 class="font-extrabold">Restaurant</h3>
              <p class="mt-2 text-sm leading-6 text-white/70">
                Usage of the Internet is becoming more common due to rapid advancement of technology and power.
              </p>
            </div>
          </div>
        </div>

        <div class="rounded-xl border border-white/20 bg-white/5 p-6 shadow-[0_0_0_1px_rgba(255,255,255,0.05)] backdrop-blur">
          <div class="flex items-start gap-3">
            <div class="mt-0.5 text-amber-400">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M7 20V4M17 20V4M3 8h18M3 16h18"/>
              </svg>
            </div>
            <div>
              <h3 class="font-extrabold">Sports Club</h3>
              <p class="mt-2 text-sm leading-6 text-white/70">
                Usage of the Internet is becoming more common due to rapid advancement of technology and power.
              </p>
            </div>
          </div>
        </div>

        <div class="rounded-xl border border-white/20 bg-white/5 p-6 shadow-[0_0_0_1px_rgba(255,255,255,0.05)] backdrop-blur">
          <div class="flex items-start gap-3">
            <div class="mt-0.5 text-amber-400">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 17c3-7 15-7 18 0M5 17v4m14-4v4M8 12h8"/>
              </svg>
            </div>
            <div>
              <h3 class="font-extrabold">Swimming Pool</h3>
              <p class="mt-2 text-sm leading-6 text-white/70">
                Usage of the Internet is becoming more common due to rapid advancement of technology and power.
              </p>
            </div>
          </div>
        </div>

        <div class="rounded-xl border border-white/20 bg-white/5 p-6 shadow-[0_0_0_1px_rgba(255,255,255,0.05)] backdrop-blur">
          <div class="flex items-start gap-3">
            <div class="mt-0.5 text-amber-400">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 16c4-6 12-6 16 0M7 16v5m10-5v5M9 12h6"/>
              </svg>
            </div>
            <div>
              <h3 class="font-extrabold">Rent a Car</h3>
              <p class="mt-2 text-sm leading-6 text-white/70">
                Usage of the Internet is becoming more common due to rapid advancement of technology and power.
              </p>
            </div>
          </div>
        </div>

        <div class="rounded-xl border border-white/20 bg-white/5 p-6 shadow-[0_0_0_1px_rgba(255,255,255,0.05)] backdrop-blur">
          <div class="flex items-start gap-3">
            <div class="mt-0.5 text-amber-400">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 20V4h12v16M8 9h8M8 13h8M8 17h8"/>
              </svg>
            </div>
            <div>
              <h3 class="font-extrabold">Gymnesium</h3>
              <p class="mt-2 text-sm leading-6 text-white/70">
                Usage of the Internet is becoming more common due to rapid advancement of technology and power.
              </p>
            </div>
          </div>
        </div>

        <div class="rounded-xl border border-white/20 bg-white/5 p-6 shadow-[0_0_0_1px_rgba(255,255,255,0.05)] backdrop-blur">
          <div class="flex items-start gap-3">
            <div class="mt-0.5 text-amber-400">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M8 8h8M7 20h10M9 8v12m6-12v12M5 10h14"/>
              </svg>
            </div>
            <div>
              <h3 class="font-extrabold">Bar</h3>
              <p class="mt-2 text-sm leading-6 text-white/70">
                Usage of the Internet is becoming more common due to rapid advancement of technology and power.
              </p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
  
  
  <!-- ABOUT SECTION -->
  <section class="py-24 md:py-32">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

      <!-- Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">

        <!-- LEFT CONTENT -->
        <div class="lg:col-span-5">
          <h2 class="text-3xl md:text-4xl font-extrabold leading-tight">
            About Us<br />
            Our History<br />
            Mission &amp; Vision
          </h2>

          <p class="mt-6 text-sm leading-6 text-slate-400 max-w-md">
            Inappropriate behavior is often laughed off as “boys will be boys,” women face higher
            conduct standards especially in the workplace. That’s why it’s crucial
            to educate the next generation of women about sexual harassment.
          </p>
            <p class="mt-4 text-sm leading-6 text-slate-400 max-w-md">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p>
        </div>
        <!-- RIGHT IMAGE -->
        <div class="lg:col-span-7">
            <img
                class="w-full rounded-xl border border-slate-200 shadow-lg"
                src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1470&q=80"
                alt="About us image"
            />
        </div>
        </div>
        <!-- END Layout -->
    </div>
  </section>


  <!-- TESTIMONIAL SECTION (matched to screenshot) -->
  <section class="bg-[#f7f9ff] py-20 md:py-24">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
      <!-- Heading -->
      <div class="text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">
          Testimonial from our Clients
        </h2>
        <p class="mt-3 text-sm text-slate-400">
          The French Revolution constituted for the conscience of the dominant aristocratic class a fall from
        </p>
      </div>

      <!-- Cards -->
      <div class="mt-14 grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10 place-items-center">
        <!-- Card 1 -->
        <div class="w-full max-w-xl bg-white border border-slate-100 shadow-sm px-8 py-8">
          <div class="flex items-start gap-6">
            <!-- avatar -->
            <img
              class="h-16 w-16 rounded-full object-cover"
              src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80"
              alt="Client avatar"
            />

            <div class="flex-1">
              <p class="text-sm leading-6 text-slate-400">
                As conscious traveling Paupers we must always be concerned about our dear Mother Earth.
                If you think about it, you travel across her face, and She is the
              </p>

              <div class="mt-4 font-extrabold">Fanny Spencer</div>

              <!-- stars -->
              <div class="mt-1 flex items-center gap-1 text-amber-400">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 15.27l-5.18 3.05 1.64-5.81L1.5 7.98l6-.52L10 2l2.5 5.46 6 .52-4.96 4.53 1.64 5.81z"/></svg>
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 15.27l-5.18 3.05 1.64-5.81L1.5 7.98l6-.52L10 2l2.5 5.46 6 .52-4.96 4.53 1.64 5.81z"/></svg>
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 15.27l-5.18 3.05 1.64-5.81L1.5 7.98l6-.52L10 2l2.5 5.46 6 .52-4.96 4.53 1.64 5.81z"/></svg>
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 15.27l-5.18 3.05 1.64-5.81L1.5 7.98l6-.52L10 2l2.5 5.46 6 .52-4.96 4.53 1.64 5.81z"/></svg>
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 15.27l-5.18 3.05 1.64-5.81L1.5 7.98l6-.52L10 2l2.5 5.46 6 .52-4.96 4.53 1.64 5.81z"/></svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="w-full max-w-xl bg-white border border-slate-100 shadow-sm px-8 py-8">
          <div class="flex items-start gap-6">
            <img
              class="h-16 w-16 rounded-full object-cover"
              src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=200&q=80"
              alt="Client avatar"
            />

            <div class="flex-1">
              <p class="text-sm leading-6 text-slate-400">
                As conscious traveling Paupers we must always be concerned about our dear Mother Earth.
                If you think about it, you travel across her face, and She is the
              </p>

              <div class="mt-4 font-extrabold">Fanny Spencer</div>

              <div class="mt-1 flex items-center gap-1 text-amber-400">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 15.27l-5.18 3.05 1.64-5.81L1.5 7.98l6-.52L10 2l2.5 5.46 6 .52-4.96 4.53 1.64 5.81z"/></svg>
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 15.27l-5.18 3.05 1.64-5.81L1.5 7.98l6-.52L10 2l2.5 5.46 6 .52-4.96 4.53 1.64 5.81z"/></svg>
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 15.27l-5.18 3.05 1.64-5.81L1.5 7.98l6-.52L10 2l2.5 5.46 6 .52-4.96 4.53 1.64 5.81z"/></svg>
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 15.27l-5.18 3.05 1.64-5.81L1.5 7.98l6-.52L10 2l2.5 5.46 6 .52-4.96 4.53 1.64 5.81z"/></svg>
                <svg class="h-4 w-4 opacity-40" viewBox="0 0 20 20" fill="currentColor"><path d="M10 15.27l-5.18 3.05 1.64-5.81L1.5 7.98l6-.52L10 2l2.5 5.46 6 .52-4.96 4.53 1.64 5.81z"/></svg>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

    <!-- LATEST POSTS -->
  <section class="py-20 md:py-24">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <!-- Heading -->
      <div class="text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">
          latest posts from blog
        </h2>
        <p class="mt-3 text-sm text-slate-400">
          The French Revolution constituted for the conscience of the dominant aristocratic class a fall from
        </p>
      </div>

      <!-- Posts grid -->
      <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-10">

        <!-- Post Card 1 -->
        <article class="group">
          <div class="overflow-hidden bg-slate-100">
            <img
              src="https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1200&q=80"
              alt="Post image"
              class="h-56 w-full object-cover transition-transform duration-500 ease-out group-hover:scale-110"
            />
          </div>

          <!-- tags -->
          <div class="mt-4 flex gap-2">
            <span class="border border-slate-200 px-3 py-1 text-[11px] font-semibold text-slate-700">Travel</span>
            <span class="border border-slate-200 px-3 py-1 text-[11px] font-semibold text-slate-700">Life Style</span>
          </div>

          <!-- title -->
          <h3 class="mt-4 text-base font-extrabold text-slate-900">
            Low Cost Advertising
          </h3>

          <!-- excerpt -->
          <p class="mt-3 text-sm leading-6 text-slate-400">
            Acres of Diamonds... you’ve read the famous story, or at least had it related to you. A farmer.
          </p>

          <!-- date -->
          <div class="mt-6 text-xs font-semibold text-slate-500">
            31st January, 2018
          </div>
        </article>

        <!-- Post Card 2 -->
        <article class="group">
          <div class="overflow-hidden bg-slate-100">
            <img
              src="https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1200&q=80"
              alt="Post image"
              class="h-56 w-full object-cover transition-transform duration-500 ease-out group-hover:scale-110"
            />
          </div>

          <div class="mt-4 flex gap-2">
            <span class="border border-slate-200 px-3 py-1 text-[11px] font-semibold text-slate-700">Travel</span>
            <span class="border border-slate-200 px-3 py-1 text-[11px] font-semibold text-slate-700">Life Style</span>
          </div>

          <h3 class="mt-4 text-base font-extrabold text-slate-900">
            Creative Outdoor Ads
          </h3>

          <p class="mt-3 text-sm leading-6 text-slate-400">
            Self-doubt and fear interfere with our ability to achieve or set goals. Self-doubt and fear are
          </p>

          <div class="mt-6 text-xs font-semibold text-slate-500">
            31st January, 2018
          </div>
        </article>

        <!-- Post Card 3 -->
        <article class="group">
          <div class="overflow-hidden bg-slate-100">
            <img
              src="https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1200&q=80"
              alt="Post image"
              class="h-56 w-full object-cover transition-transform duration-500 ease-out group-hover:scale-110"
            />
          </div>

          <div class="mt-4 flex gap-2">
            <span class="border border-slate-200 px-3 py-1 text-[11px] font-semibold text-slate-700">Travel</span>
            <span class="border border-slate-200 px-3 py-1 text-[11px] font-semibold text-slate-700">Life Style</span>
          </div>

          <h3 class="mt-4 text-base font-extrabold text-slate-900">
            It S Classified How To Utilize Free
          </h3>

          <p class="mt-3 text-sm leading-6 text-slate-400">
            Why do you want to motivate yourself? Actually, just answering that question fully can
          </p>

          <div class="mt-6 text-xs font-semibold text-slate-500">
            31st January, 2018
          </div>
        </article>

      </div>
    </div>
  </section>
@endsection