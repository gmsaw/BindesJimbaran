<html lang="en" x-data="{ mobileSidebarOpen: false, sidebarOpen: true, showLogoutConfirmation: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm z-10">
        <div class="flex items-center justify-between px-4 py-4 sm:px-6">
            <div class="flex items-center">
                <button @click="mobileSidebarOpen = true" class="mr-2 text-gray-500 hover:text-gray-600 lg:hidden">
                    <i class="fas fa-bars"></i>
                </button>
                <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:block mr-2 text-gray-500 hover:text-gray-600">
                    <i class="fas fa-bars"></i>
                </button>
                <h2 class="text-lg font-medium text-gray-800">Dashboard</h2>
            </div>
            
            <div class="flex items-center space-x-4">
                <button class="p-1 text-gray-400 hover:text-gray-500">
                    <i class="fas fa-bell"></i>
                </button>
                
                <!-- Dropdown User Menu -->
                <div class="relative" x-data="{ open: false }">
                
                @auth
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                    <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}" alt="User">
                    <span class="hidden md:inline"> {{ auth()->user()->name }}</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>

                @else
                <div class="flex items-center space-x-2 focus:outline-none">
                    <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=guest" alt="User">
                    <span class="hidden md:inline">Guest</span>
                    <!-- <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i> -->
                </div>
                @endauth

                    
                    <!-- Dropdown Content -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i> Profil
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i> Pengaturan
                        </a>
                        <div class="border-t border-gray-200"></div>
                        <button @click="open = false; showLogoutConfirmation = true" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Logout Confirmation Modal -->
    <div x-cloak>
        <!-- Modal Overlay -->
        <div x-show="showLogoutConfirmation" 
             class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
            <!-- Modal Content -->
            <div x-show="showLogoutConfirmation"
                 @click.away="showLogoutConfirmation = false"
                 class="bg-white rounded-lg shadow-xl max-w-md w-full"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95">
                
                <form action="/logout" method="POST">
                @csrf
                @method('POST')
                 <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <i class="fas fa-exclamation text-red-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Konfirmasi Logout</h3>
                        </div>
                    </div>
                    
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Apakah Anda yakin ingin keluar dari sistem?
                        </p>
                    </div>
                    
                    <div class="mt-4 flex justify-end space-x-3">
                        <button @click="showLogoutConfirmation = false" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Ya, Logout
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>