<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MyWebApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <!-- Nama Aplikasi -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-indigo-600">Desa Adat Jimbaran</h1>
            <p class="text-gray-600 mt-2">Silakan masuk ke akun Anda</p>
        </div>
        
        <!-- Error Message -->
        @error('email')
        <div class="mb-6 p-4 border-l-4 border-red-500 bg-red-50 rounded">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                    <i class="fas fa-exclamation text-red-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-red-800">Login Gagal</h3>
                    <div class="mt-1 text-sm text-red-700">
                        <p>{{ $message }}</p>
                    </div>
                </div>
            </div>
        </div>
        @enderror
        
        <!-- Form Login -->
        <form id="loginForm" class="space-y-4" action="/login" method="POST">
            @csrf
            @method('POST')
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" 
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                </div>
                
                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Lupa password?</a>
                </div>
            </div>
            
            <div>
                <button type="submit" 
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Masuk
                </button>
            </div>
        </form>
        
        <!-- Link Daftar -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Belum punya akun? 
                <a href="/register" class="font-medium text-indigo-600 hover:text-indigo-500">Daftar sekarang</a>
            </p>
        </div>
    </div>
</body>
</html>