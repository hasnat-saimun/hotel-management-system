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
 
  @endsection