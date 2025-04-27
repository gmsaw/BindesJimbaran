<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Adat Jimbaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full" x-data="{ sidebarOpen: window.innerWidth >= 1024, mobileSidebarOpen: false }" x-cloak>
    
<div class="flex h-full">
        <!-- Side Bar -->
        <x-sidebar ></x-sidebar>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden"
             :class="{
                 'lg:ml-64': sidebarOpen,
                 'ml-0': !sidebarOpen
             }">

            <x-header></x-header>

            <!-- Main Layout -->
            @yield('maincontent')
            
        </div>
    </div>

    
</body>
</html>