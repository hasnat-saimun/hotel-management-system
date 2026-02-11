
<!-- HEADER -->
    <header class="w-full bg-white border-b border-slate-100">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
          <!-- Logo -->
          <a href="#" class="flex items-center gap-3">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded bg-amber-500 text-white font-bold">👑</span>
            <span class="text-sm font-semibold tracking-wide">ROYAL HOTEL</span>
          </a>

          <!-- Nav -->
          <nav class="hidden md:flex items-center gap-8 text-sm font-semibold">
            <a class="{{ url()->current() == url('/') ? 'text-sky-500' : 'hover:text-sky-600' }}" href="{{ url('/') }}">HOME</a>
            <a class="{{ request()->is('about') ? 'text-sky-500' : 'hover:text-sky-600' }}" href="{{ url('/about') }}">ABOUT US</a>
            <a class="{{ request()->is('accomodation*') ? 'text-sky-500' : 'hover:text-sky-600' }}" href="{{ url('/accomodation') }}">ACCOMODATION</a>
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