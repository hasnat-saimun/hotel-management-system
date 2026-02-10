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
    
@endsection