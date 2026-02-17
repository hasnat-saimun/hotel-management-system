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

  <!-- text section -->
   <section class="py-12">
  <div class="mx-auto max-w-5xl px-6">

    <p class="text-sm md:text-base leading-7 text-[#8a8178] mb-4">
      We appreciate that plans change and you may need to cancel or postpone your travel
      arrangements. Therefore, most of our rates have simple, no-hassle cancellation
      policies. Retrieve your booking to read the Cancellation policy that applies to your
      specific itinerary.
    </p>

    <p class="text-sm md:text-base leading-7 text-[#8a8178]">
      Please note: online cancellations are only available for hotel bookings made on the
      Hamilton Island website. For all other cancellations please contact your booking agent.
    </p>

  </div>
</section>

<!-- exit from booking -->
 <section class="bg-[#fbf7f1] py-12">
  <div class="mx-auto max-w-4xl px-6">

    <!-- Title -->
    <h2 class="text-2xl font-medium text-[#6f675d] mb-6">
      Retrieve your booking
    </h2>

    <!-- Form -->
    <form action="" method="POST" class="space-y-5">
      @csrf

      <!-- Booking Reference -->
      <input
        type="text"
        name="booking_reference"
        placeholder="Interim Booking ID (13 digit number starting with 23843)
"
        required
        class="w-full rounded border border-[#e5dfd6] bg-[#fffaf3] px-4 py-3
               text-sm text-[#6f675d] placeholder:text-[#b3aba1]
               focus:border-[#9b9488] focus:outline-none"
      />

      <!-- Email -->
      <input
        type="email"
        name="email"
        placeholder="Email address"
        required
        class="w-full rounded border border-[#e5dfd6] bg-[#fffaf3] px-4 py-3
               text-sm text-[#6f675d] placeholder:text-[#b3aba1]
               focus:border-[#9b9488] focus:outline-none"
      />

      <!-- Button -->
      <button
        type="submit"
        class="inline-flex items-center rounded bg-[#7c826c]
               px-6 py-3 text-sm font-medium text-white
               hover:bg-[#6e745f] transition"
      >
        Retrieve My Booking
      </button>

    </form>
  </div>
</section>
  @endsection