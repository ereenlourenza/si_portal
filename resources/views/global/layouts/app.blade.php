<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GPIB Immanuel Malang</title>

  @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Tailwind + Alpine --}}

  {{-- Alpine --}}
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

  {{-- Icon --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  {{-- Font --}}
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Platypi:ital,wght@1,400&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">

  @stack('css')
</head>
<body class="bg-white text-[#231C0D]">
  {{-- <div class="sticky top-0 z-50"> --}}
    @include('global.partials.navbar') {{-- Header/Navbar --}}
  {{-- </div> --}}

  <main class="min-h-screen">
    @yield('content') {{-- Konten halaman --}}
  </main>

  @include('global.partials.footer')

  @stack('js')
</body>
</html>
