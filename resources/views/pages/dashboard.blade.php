<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Adat Jimbaran Dashboard</title>
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
    <!-- Main Layout -->
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

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
                <!-- Stats Cards -->
                <x-widget.stats-cards />

                <!-- Charts -->
                <div class="mt-5 flex justify-center">
                    <x-widget.population-chart />
                    <div class="grid grid-rows-2">
                        <x-widget.gender-chart />
                        <x-widget.calendar-dashboard />
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="mt-8">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all</a>
                    </div>
                    
                    <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200">
                            <li class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">New order received</p>
                                    <p class="text-sm text-gray-500">2h ago</p>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Order #12345 from John Smith</p>
                            </li>
                            <li class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">New user registered</p>
                                    <p class="text-sm text-gray-500">5h ago</p>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Jane Doe (jane@example.com)</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Initialize Alpine.js store
        document.addEventListener('alpine:init', () => {
            Alpine.store('dashboard', {
                stats: {
                    visitors: 24543,
                    revenue: 12345,
                    projects: 12,
                    conversion: 3.2
                },
                recentActivities: [
                    { id: 1, title: 'New order received', time: '2h ago', description: 'Order #12345 from John Smith' },
                    { id: 2, title: 'New user registered', time: '5h ago', description: 'Jane Doe (jane@example.com)' }
                ]
            });
            
            // Handle window resize
            window.addEventListener('resize', () => {
                Alpine.store('sidebarOpen', window.innerWidth >= 1024);
            });
        });
    </script>
</body>
</html>