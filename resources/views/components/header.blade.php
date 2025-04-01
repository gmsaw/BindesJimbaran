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
                        <h2 class="text-lg font-medium text-gray-800">{{ $headerTitle[$currentRoute]['title'] }}</h2>
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