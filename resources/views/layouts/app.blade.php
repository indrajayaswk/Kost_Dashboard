<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

</head>

{{-- All CRUD components can be included here or inside the body --}}

@include('admin.dashboard.dashboard_add')
@include('admin.dashboard.dashboard_update')
@include('admin.dashboard.dashboard_read')
@include('admin.dashboard.dashboard_delete')

@include('admin.penghuni.penghuni-add')
@include('admin.penghuni.penghuni-update')

@include('admin.kamar.kamar-add')
@include('admin.kamar.kamar-update')
{{-- ---------------------------------------------------------- --}}
@include('admin2.tenant.tenant-add')
@include('admin2.room.room-add')
@include('admin2.meter.meter-add')

{{-- ------------------------ End of CRUD includes ------------------------ --}}

<body class="font-sans antialiased ">
    {{-- background abu abu, kalau ada yang rusak, uncomment ini
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">  --}}
        
        <!-- Sidebar -->
        <x-sidebar2 /> 

        <!-- Main Content -->
        <div class="flex-1">
            @include('layouts.navigation2')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    {{-- </div> --}}

</body>
</html>
