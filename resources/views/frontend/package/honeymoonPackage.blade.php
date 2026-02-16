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
  <!-- SECTION 1: Image collage + Beige content card -->
  <section class="pb-20 py-16">
    
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <div class="relative">
        <!-- Beige panel (right) -->
        <div class="absolute right-0 top-15 hidden md:block h-[400px] w-[92%] bg-[#efe7dc]"></div>

        <div class="relative grid grid-cols-1 md:grid-cols-12 gap-10 items-start">

          <!-- Left images (overlapping like screenshot) -->
          <div class="md:col-span-6">
                <div class="relative">
                <!-- Big image -->
                <div class="bg-white">
                    <img
                    class="w-full h-[360px] object-cover"
                    src="https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1400&q=80"
                    alt="Beach"
                    />
                </div>

                <!-- Small strip image (bottom overlap) -->
                
                </div>
          </div>

          <!-- Right text on beige -->
          <div class="md:col-span-6 md:pl-10 md:pt-35">
            <div class="relative md:pr-10">
              <h2 class="text-2xl font-light text-[#6a5c52]">
                Couple’s Indulgence
              </h2>

              <p class="mt-6 text-sm leading-7 text-[#7a7a7a] max-w-lg">
                Enjoy three nights of luxury and experience a romantic qualia getaway including a bottle of
                Champagne; enjoy a magnificent and memorable sunset cruise on board one of qualia's luxurious cruisers;
                and feast on a perfectly prepared gourmet qualia picnic on a secluded beach. This is the Hamilton Island life.
              </p>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>

  <!-- SECTION 2: What's included + right image -->
  <section class=" pt-50">
    
    
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
    
      <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start">

        <!-- Left list -->
        <div class="md:col-span-6">
          <h3 class="text-xl font-light text-[#6a5c52]">What's Included</h3>

          <ul class="mt-6 space-y-3 text-sm leading-7 text-[#7a7a7a]">
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>Seven nights' accommodation at qualia, including one complimentary night</span></li>
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>A bottle of French Champagne on arrival</span></li>
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>Dinner for two on one evening at the Long Pavilion or the four-course Pebble Beach Collection at Pebble Beach (excluding alcoholic beverages)</span></li>
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>A la carte breakfast daily at the Long Pavilion</span></li>
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>All non-alcoholic beverages at qualia, including soft drinks, juices, waters, tea and coffee (excluding blended beverages)</span></li>
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>Use of an electric golf buggy for the duration of your stay (driver’s licence required)</span></li>
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>24-hour chauffeur service around Hamilton Island</span></li>
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>VIP return Hamilton Island airport or marina transfers</span></li>
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>Airport Lounge access</span></li>
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>Use of non-motorised watercraft</span></li>
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>Use of the qualia gym, spa, sauna and access to tennis court hire</span></li>
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>Complimentary WiFi</span></li>
            <li class="flex gap-3"><span class="text-[#8e8a84]">—</span><span>Eligible for Velocity Points when qualia accommodation is booked direct (conditions apply)</span></li>
          </ul>
        </div>

        <!-- Right image -->
        <div class="md:col-span-6">
          <div class="bg-white">
            <img
              class="w-full h-[340px] object-cover"
              src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1400&q=80"
              alt="Dining by sea"
            />
          </div>
        </div>

      </div>

    </div>
  </section>

   <section class=" py-16">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <div class="relative">
        <!-- Beige panel (right) -->
        <div class="absolute left-0 top-15 hidden md:block h-[350px] w-[90%] bg-[#efe7dc]"></div>

        <div class="relative grid grid-cols-1 md:grid-cols-12 gap-10 items-start">

          <!-- Left images (overlapping like screenshot) -->
          <div class="md:col-span-6">
            <div class="relative left-20 md:pr-10 px-6 md:px-0 py-30 ">
              <h2 class="text-[26px] font-light text-[#6a5c52] tracking-wide">
          Leeward Pavilion
        </h2>

        <p class="mt-6 text-[15px] leading-7 text-[#7a7a7a] max-w-md">
          Perfectly positioned accommodation to take in sea views,
          Australian bushland and the Whitsundays.
        </p>

        <p class="mt-6 text-[15px] font-semibold text-[#6a5c52]">
          3 night package from $6,358* twin share
        </p>

        <button
          class="mt-10 inline-flex items-center justify-center
                 px-10 h-11 bg-[#8b8a83]
                 text-white text-sm tracking-widest
                 hover:bg-[#77756f] transition">
          BOOK
        </button>
            </div>
                
          </div>

          <!-- Right text on beige -->
          <div class="md:col-span-6 md:pl-10 md:pt-35">
            <div class="relative">
                <!-- Big image -->
                <div class="bg-white">
                    <img
                    class="w-full h-[350px] object-cover"
                    src="https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1400&q=80"
                    alt="Beach"
                    />
                </div>

                <!-- Small strip image (bottom overlap) -->
                
                </div>
          </div>

        </div>
      </div>

    </div>
  </section>
  

   <!-- SECTION 1: Image collage + Beige content card -->
  <section class="  ">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

      <div class="relative">
        <!-- Beige panel (right) -->
        <div class="absolute right-0 top-15 hidden md:block h-[350px] w-[90%] bg-[#efe7dc]"></div>

        <div class="relative grid grid-cols-1 md:grid-cols-12 gap-10 items-start">

          <!-- Left images (overlapping like screenshot) -->
          <div class="md:col-span-6 ">
                <div class="relative">
                <!-- Big image -->
                <div class="bg-white">
                    <img
                    class="w-full h-[350px] object-cover"
                    src="https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1400&q=80"
                    alt="Beach"
                    />
                </div>

                <!-- Small strip image (bottom overlap) -->
                
                </div>
          </div>

          <!-- Right text on beige -->
          <div class="md:col-span-6 md:pl-10 md:pt-35">
            <div class="relative md:pr-10">
               <h2 class="text-[26px] font-light text-[#6a5c52] tracking-wide">
                    Leeward Pavilion
                </h2>

                <p class="mt-6 text-[15px] leading-7 text-[#7a7a7a] max-w-md">
                Perfectly positioned accommodation to take in sea views,
                Australian bushland and the Whitsundays.
                </p>

                <p class="mt-6 text-[15px] font-semibold text-[#6a5c52]">
                3 night package from $6,358* twin share
                </p>

                <button
                class="mt-10 inline-flex items-center justify-center
                        px-10 h-11 bg-[#8b8a83]
                        text-white text-sm tracking-widest
                        hover:bg-[#77756f] transition">
                BOOK
                </button>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>

  
<!-- Pre-Book Dining Section -->
  <section class="relative py-24 overflow-hidden">

    <!-- subtle background pattern -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(0,0,0,0.04),transparent_60%)]"></div>

    <div class="relative mx-auto max-w-4xl px-6 text-center">

      <!-- Title -->
      <h2 class="text-[26px] md:text-[30px] font-light tracking-wide text-[#6a5c52]">
        Pre-Book Dining, Tours and Activities
      </h2>

      <!-- Description -->
      <p class="mt-6 text-sm md:text-base leading-7 text-[#8a8178]">
        Pre-booking is recommended for dining, spa treatments, golf and other tours
        and activities.
      </p>

    </div>
  </section>  

   <!-- You May Also Like -->
  <section class="py-20">
    <div class="mx-auto max-w-6xl px-6">

      <!-- Title -->
      <h2 class="text-center text-[26px] font-light tracking-wide text-[#6a5c52]">
        You May Also Like
      </h2>

      <!-- Slider -->
      <div class="mt-12">

        <div
          id="alsoLikeTrack"
          class="flex gap-12 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2"
        >

        <!-- Card 1 -->
        <a href="#" class="group block shrink-0 w-full md:w-[calc(50%-1.5rem)] snap-start">
          <div class="overflow-hidden bg-white">
            <img
              class="w-full h-[340px] object-cover transition duration-500 group-hover:scale-[1.03]"
              src="https://images.unsplash.com/photo-1518837695005-2083093ee35b?auto=format&fit=crop&w=1600&q=80"
              alt="Luxury Heart Island Adventure"
            />
          </div>
          <p class="mt-4 text-sm text-[#6a5c52]">
            Luxury Heart Island Adventure
          </p>
        </a>

        <!-- Card 2 -->
        <a href="#" class="group block shrink-0 w-full md:w-[calc(50%-1.5rem)] snap-start">
          <div class="overflow-hidden bg-white">
            <img
              class="w-full h-[340px] object-cover transition duration-500 group-hover:scale-[1.03]"
              src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80"
              alt="Golf Getaway"
            />
          </div>
          <p class="mt-4 text-sm text-[#6a5c52]">
            Golf Getaway
          </p>
        </a><!-- Card 2 -->

        <!-- Card 3 -->
        <a href="#" class="group block shrink-0 w-full md:w-[calc(50%-1.5rem)] snap-start">
          <div class="overflow-hidden bg-white">
            <img
              class="w-full h-[340px] object-cover transition duration-500 group-hover:scale-[1.03]"
              src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80"
              alt="Golf Getaway"
            />
          </div>
          <p class="mt-4 text-sm text-[#6a5c52]">
            Golf Getaway
          </p>
        </a>

        </div>

      </div>

      <!-- Dots + Arrows -->
      <div class="mt-12 flex items-center justify-center gap-6 text-[#6a5c52]">

        <!-- Left arrow -->
        <button id="alsoLikePrev" class="text-xl leading-none hover:opacity-70 transition" aria-label="Prev">
          ←
        </button>

        <!-- Dots -->
        <div id="alsoLikeDots" class="flex items-center gap-2" aria-label="Slider position">
          <span class="h-1.5 w-1.5 rounded-full bg-[#6a5c52]" data-dot="0"></span>
          <span class="h-1.5 w-1.5 rounded-full bg-[#6a5c52]/40" data-dot="1"></span>
          <span class="h-1.5 w-1.5 rounded-full bg-[#6a5c52]/40" data-dot="2"></span>
        </div>

        <!-- Right arrow -->
        <button id="alsoLikeNext" class="text-xl leading-none hover:opacity-70 transition" aria-label="Next">
          →
        </button>

      </div>

    </div>
  </section>

  @push('scripts')
    <script>
      (function () {
        var track = document.getElementById('alsoLikeTrack');
        var prevBtn = document.getElementById('alsoLikePrev');
        var nextBtn = document.getElementById('alsoLikeNext');
        var dotsWrap = document.getElementById('alsoLikeDots');

        if (!track || !prevBtn || !nextBtn || !dotsWrap) return;

        function getStep() {
          var firstSlide = track.querySelector('a');
          if (!firstSlide) return track.clientWidth;
          var styles = window.getComputedStyle(track);
          var gap = parseFloat(styles.columnGap || styles.gap || '0') || 0;
          return firstSlide.getBoundingClientRect().width + gap;
        }

        function setActiveDot(index) {
          var dots = dotsWrap.querySelectorAll('[data-dot]');
          dots.forEach(function (dot) {
            dot.classList.remove('bg-[#6a5c52]');
            dot.classList.add('bg-[#6a5c52]/40');
          });
          var active = dotsWrap.querySelector('[data-dot="' + index + '"]');
          if (active) {
            active.classList.remove('bg-[#6a5c52]/40');
            active.classList.add('bg-[#6a5c52]');
          }
        }

        function getActiveIndex() {
          var step = getStep();
          if (!step) return 0;
          return Math.max(0, Math.round(track.scrollLeft / step));
        }

        prevBtn.addEventListener('click', function () {
          track.scrollBy({ left: -getStep(), behavior: 'smooth' });
        });

        nextBtn.addEventListener('click', function () {
          track.scrollBy({ left: getStep(), behavior: 'smooth' });
        });

        track.addEventListener('scroll', function () {
          window.requestAnimationFrame(function () {
            setActiveDot(getActiveIndex());
          });
        });

        setActiveDot(0);
      })();
    </script>
  @endpush
@endsection