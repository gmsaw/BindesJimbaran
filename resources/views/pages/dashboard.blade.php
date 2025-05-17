@extends('layouts.dasboard-layout')

@section('maincontent')

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
        <!-- Stats Cards -->
        <x-widget.stats-cards :total-penduduk="$totalPenduduk"
                              :krama-adat="$kramaAdat"
                              :krama-tamiu="$kramaTamiu"
                              :tamiu="$tamiu"/>

        <!-- Charts -->
        <div class="mt-5 flex justify-center">
            <x-widget.population-chart />
            <div class="grid grid-rows-2">
{{--                <x-widget.gender-chart-components />--}}
                <x-widget.calendar-dashboard />
            </div>
        </div>

        <!-- Recent Activity -->
{{--        <div class="mt-8">--}}
{{--            <div class="flex items-center justify-between">--}}
{{--                <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>--}}
{{--                <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all</a>--}}
{{--            </div>--}}
{{--            --}}
{{--            <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">--}}
{{--                <ul class="divide-y divide-gray-200">--}}
{{--                    <li class="px-4 py-4 sm:px-6">--}}
{{--                        <div class="flex items-center justify-between">--}}
{{--                            <p class="text-sm font-medium text-indigo-600 truncate">New order received</p>--}}
{{--                            <p class="text-sm text-gray-500">2h ago</p>--}}
{{--                        </div>--}}
{{--                        <p class="mt-1 text-sm text-gray-500">Order #12345 from John Smith</p>--}}
{{--                    </li>--}}
{{--                    <li class="px-4 py-4 sm:px-6">--}}
{{--                        <div class="flex items-center justify-between">--}}
{{--                            <p class="text-sm font-medium text-indigo-600 truncate">New user registered</p>--}}
{{--                            <p class="text-sm text-gray-500">5h ago</p>--}}
{{--                        </div>--}}
{{--                        <p class="mt-1 text-sm text-gray-500">Jane Doe (jane@example.com)</p>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}
    </main>

    <script>
        // Initialize Alpine.js store
        document.addEventListener('alpine:init', () => {
            Alpine.store('dashboard', {
                stats: {
                    // visitors: 24543,
                    // revenue: 12345,
                    // projects: 12,
                    // conversion: 3.2
                },
                recentActivities: [
                    // { id: 1, title: 'New order received', time: '2h ago', description: 'Order #12345 from John Smith' },
                    // { id: 2, title: 'New user registered', time: '5h ago', description: 'Jane Doe (jane@example.com)' }
                ]
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                Alpine.store('sidebarOpen', window.innerWidth >= 1024);
            });
        });
    </script>
@endsection
