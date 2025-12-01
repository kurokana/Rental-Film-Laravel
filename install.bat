@echo off
echo ====================================
echo Instalasi Sistem Rental Film
echo ====================================
echo.

echo [1/8] Installing Composer Dependencies...
call composer install
if %errorlevel% neq 0 (
    echo Error installing composer dependencies!
    pause
    exit /b %errorlevel%
)
echo.

echo [2/8] Installing NPM Dependencies...
call npm install
if %errorlevel% neq 0 (
    echo Error installing npm dependencies!
    pause
    exit /b %errorlevel%
)
echo.

echo [3/8] Copying .env file...
if not exist .env (
    copy .env.example .env
    echo .env file created
) else (
    echo .env file already exists
)
echo.

echo [4/8] Generating Application Key...
call php artisan key:generate
echo.

echo [5/8] Installing PDF Package...
call composer require barryvdh/laravel-dompdf
echo.

echo [6/8] Running Migrations and Seeders...
call php artisan migrate:fresh --seed
if %errorlevel% neq 0 (
    echo Error running migrations!
    echo Please check your database configuration in .env file
    pause
    exit /b %errorlevel%
)
echo.

echo [7/8] Creating Storage Link...
call php artisan storage:link
echo.

echo [8/8] Building Assets...
call npm run build
echo.

echo ====================================
echo Instalasi Selesai!
echo ====================================
echo.
echo Untuk menjalankan aplikasi:
echo 1. Jalankan: php artisan serve
echo 2. Buka browser: http://localhost:8000
echo.
echo Default Login:
echo - Owner: owner@rentalfilm.com / password
echo - Pegawai: staff@rentalfilm.com / password
echo - User: john@example.com / password
echo.
pause
