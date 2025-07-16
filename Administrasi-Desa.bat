@echo off
title Laravel Starter

echo Memulai Server Development Laravel...
echo.

:: Memulai PHP Artisan Serve di jendela terminal baru
start "PHP Artisan Server" php artisan serve

:: Memulai NPM Run Dev di jendela terminal baru
start "NPM Dev Server" npm run dev

:: Beri waktu 5 detik agar server siap
echo Menunggu server siap dalam 5 detik...
timeout /t 5 > nul

:: Membuka browser ke alamat server
echo Membuka browser...
start http://127.0.0.1:8000

exit
