<!-- Sidebar Backdrop (Mobile) -->
<div x-show="mobileSidebarOpen" @click="mobileSidebarOpen = false" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"></div>

<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transform transition-all duration-300 ease-in-out"
       x-show="sidebarOpen || mobileSidebarOpen"
       @click.outside="mobileSidebarOpen = false"
       :class="{
           '-translate-x-full lg:translate-x-0': !mobileSidebarOpen && !sidebarOpen,
           'translate-x-0': mobileSidebarOpen || sidebarOpen
       }">
    <div class="flex items-center justify-between px-4 py-5 border-b">
        <h1 class="text-xl font-bold text-indigo-600">Desa Adat Jimbaran</h1>
        <button @click="mobileSidebarOpen = false; sidebarOpen = false" 
                class="p-1 rounded-md text-gray-500 hover:bg-gray-100 lg:hidden">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <nav class="px-2 py-4 overflow-y-auto h-[calc(100%-80px)]">
        <div class="space-y-1">
        @foreach($menuItems as $route => $item)
            <a href="{{ route($route) }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive($route) ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <i class="fas fa-{{ $item['icon'] }} mr-3 {{ $isActive($route) ? 'text-indigo-500' : 'text-gray-400' }}"></i>
            {{ $item['label'] }}
            </a>
        @endforeach
        </div>
        
        <div class="mt-8">
            <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tools</h3>
            <div class="mt-1 space-y-1">
            @foreach($toolsItems as $route => $item)
                <a href="{{ route($route) }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive($route) ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-{{ $item['icon'] }} mr-3 {{ $isActive($route) ? 'text-indigo-500' : 'text-gray-400' }}"></i>
                {{ $item['label'] }}
                 </a>
            @endforeach
            </div>
        </div>
    </nav>
</aside>