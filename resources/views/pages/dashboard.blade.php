<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Adat Jimbaran Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
                        <h2 class="text-lg font-medium text-gray-800">Dashboard Overview</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <button class="p-1 text-gray-400 hover:text-gray-500">
                            <i class="fas fa-bell"></i>
                        </button>
                        <div class="relative">
                            <button class="flex items-center space-x-2">
                                <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=Admin" alt="User">
                                <span class="hidden md:inline">Admin</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 gap-5 mt-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="p-5 bg-white rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 truncate">Total Visitors</p>
                                <p class="mt-1 text-3xl font-semibold text-gray-900">24,543</p>
                            </div>
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-5 bg-white rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 truncate">Total Revenue</p>
                                <p class="mt-1 text-3xl font-semibold text-gray-900">$12,345</p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-5 bg-white rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 truncate">Active Projects</p>
                                <p class="mt-1 text-3xl font-semibold text-gray-900">12</p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-tasks"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-5 bg-white rounded-lg shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 truncate">Conversion Rate</p>
                                <p class="mt-1 text-3xl font-semibold text-gray-900">3.2%</p>
                            </div>
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
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