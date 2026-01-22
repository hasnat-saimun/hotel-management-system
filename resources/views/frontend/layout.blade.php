<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import 'tailwindcss';
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
        @theme {
            --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
                'Segoe UI Symbol', 'Noto Color Emoji';
        }
        body {
            font-family: 'Roboto', sans-serif;
        }

      </style>
    @stack('styles')
  </head>
  <body>
    
    @include('frontend.partials.header')
    @yield('content')
    @include('frontend.partials.footer')
    @stack('scripts')
  </body>
</html>