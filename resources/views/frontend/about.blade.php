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
@endsection