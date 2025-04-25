@extends('layouts.dasboard-layout')

@section('maincontent')

<div class="flex-1 mx-8 mt-3 mb-3 bg-white rounded-xl shadow-md overflow-y-auto p-6">
        <!-- Header -->
        <div class="flex items-center justify-between border-b pb-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-cog mr-2 text-blue-500"></i> Pengaturan Aplikasi
            </h1>
            <span class="text-sm text-gray-500">Versi 1.0.0</span>
        </div>

        <!-- Account Section -->
        @include('pages.settings.account_setting')

        <!-- Notifications Section -->
        @include('pages.settings.notification_setting')

        <!-- Appearance Section -->
        @include('pages.settings.appearence_setting')

        <!-- Language Section -->
        @include('pages.settings.language_setting')

        <!-- Danger Zone -->
        @include('pages.settings.danger_setting')

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3 mt-8 pt-4">
            <button class="px-6 py-2 border rounded-lg text-gray-700 hover:bg-gray-100 transition">
                <i class="fas fa-times mr-1"></i> Batal
            </button>
            <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
            </button>
        </div>

        <!-- Copyright Section -->
        <div class="mt-12 pt-4 border-t text-center text-sm text-gray-500">
            <p>
                &copy; <span id="copyright-year"></span> WebApp Desa Adat Jimbaran. All rights reserved.
            </p>
            <p class="mt-1">
                Developed with <i class="fas fa-heart text-red-500"></i> by Tim Pengembang Desa
            </p>
        </div>
</div>

<script>
    // Set copyright year automatically
    document.getElementById('copyright-year').textContent = new Date().getFullYear();
</script>

    

@endsection