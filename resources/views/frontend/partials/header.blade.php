
<!-- HEADER -->
  <header class="relative z-50 w-full bg-white border-b border-slate-100">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
          <!-- Logo -->
          <a href="{{ url('/') }}" class="flex items-center gap-3">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded bg-amber-500 text-white font-bold">👑</span>
            <span class="text-sm font-semibold tracking-wide">ROYAL HOTEL</span>
          </a>

          <!-- Nav -->
          <nav class="hidden md:flex items-center gap-8 text-sm font-semibold">
            
            <a class="{{ request()->is('about') ? 'text-sky-500' : 'hover:text-sky-600' }}" href="{{ url('/about') }}">ABOUT US</a>
            <a class="{{ request()->is('accomodation*') ? 'text-sky-500' : 'hover:text-sky-600' }}" href="{{ url('/accomodation') }}">ACCOMODATION</a>

            <!-- Package dropdown -->
            <div class="relative group">
              <button
                type="button"
                class="inline-flex items-center gap-1 hover:text-sky-600 focus:outline-none"
              >
                PACKAGE
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.936a.75.75 0 1 1 1.08 1.04l-4.24 4.5a.75.75 0 0 1-1.08 0l-4.24-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                </svg>
              </button>

              <div class="absolute 0 top-full z-50 mt-3 hidden w-150 rounded-md border border-slate-100 bg-white py-2 shadow-lg group-hover:block group-focus-within:block">
                <a href="{{ route('frontend.package.honeymoon_package') }}" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Honeymoon Package</a>
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Family Package</a>
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Paly Package</a>
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Mitting Package</a>
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Signature Experience</a>
              </div>
            </div>

            <!-- Taste dropdown -->
            <div class="relative group">
              <button
                type="button"
                class="inline-flex items-center gap-1 hover:text-sky-600 focus:outline-none"
              >
                Taste
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.936a.75.75 0 1 1 1.08 1.04l-4.24 4.5a.75.75 0 0 1-1.08 0l-4.24-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                </svg>
              </button>

              <div class="absolute 0 top-full z-50 mt-3 hidden w-150 rounded-md border border-slate-100 bg-white py-2 shadow-lg group-hover:block group-focus-within:block">
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Breakfast</a>
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Lunch</a>
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Paly Package</a>
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Mitting Package</a>
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Signature Experience</a>
              </div>
            </div>
            
            <!-- Activities and Experiences dropdown -->
            <div class="relative group">
              <button
                type="button"
                class="inline-flex items-center gap-1 hover:text-sky-600 focus:outline-none"
              >
                Activities and Experiences
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.936a.75.75 0 1 1 1.08 1.04l-4.24 4.5a.75.75 0 0 1-1.08 0l-4.24-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
                </svg>
              </button>

              <div class="absolute 0 top-full z-50 mt-3 hidden w-150 rounded-md border border-slate-100 bg-white py-2 shadow-lg group-hover:block group-focus-within:block">
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Golf</a>
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Spa</a>
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Paly Package</a>
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Mitting Package</a>
                <a href="#" class="mt-2 block px-4 py-2 text-sm hover:bg-slate-50 hover:text-sky-600">Signature Experience</a>
              </div>
            </div>
            
            <a class="{{ request()->is('gallery*') ? 'text-sky-500' : 'hover:text-sky-600' }}" href="{{ url('/gallery') }}">GALLERY</a>
            <a class="{{ request()->is('blog*') ? 'text-sky-500' : 'hover:text-sky-600' }}" href="#">BLOG</a>
            <a class="{{ request()->is('elements*') ? 'text-sky-500' : 'hover:text-sky-600' }}" href="{{ url('/elements') }}">ELEMENTS</a>
            <a class="{{ request()->is('contract*') ? 'text-sky-500' : 'hover:text-sky-600' }}" href="{{ url('/contract') }}">CONTACT</a>
          </nav>

          <button class="md:hidden inline-flex items-center justify-center rounded-md border border-slate-200 px-3 py-2 text-sm font-semibold hover:bg-slate-50" type="button">
            Menu
          </button>
        </div>
      </div>
    </header>