<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', app_setting('app_name', 'My App'))</title>
    {{-- Responsive --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Theme Color (PWA friendly) --}}
    <meta name="theme-color" content="#0A5C36">
    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Global CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- Font / Icon (opsional) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- Page Specific CSS --}}
    @stack('styles')
    @livewireStyles
</head>
<body>
    {{-- ================= HEADER ================= --}}
    @hasSection('header')
        <header>
            @yield('header')
        </header>
    @endif
    {{-- ================= MAIN CONTENT ================= --}}
    <main>
        @yield('content')
    </main>
    {{-- ================= GLOBAL JS ================= --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script> --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- Page Specific JS --}}
    @stack('scripts')
    @livewireScripts
    
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global delete confirmation
        function confirmDelete(title = 'Apakah Anda yakin?', text = "Data yang dihapus tidak dapat dikembalikan!", icon = 'warning') {
            return Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            });
        }
    </script>
</body>
</html>
