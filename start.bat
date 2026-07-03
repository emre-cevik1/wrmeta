@echo off
title WR META - Development Server
echo ====================================================
echo   Wild Rift Meta Tracker - Development Servers
echo ====================================================
echo.

REM PHP'nin yüklü olup olmadığını kontrol et
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo [HATA] PHP sisteminizde bulunamadi. Lutfen PHP 8+ yukleyin ve PATH'e ekleyin.
    pause
    exit /b
)

echo [1] Backend API baslatiliyor (Port: 8000)...
start "WR META - Backend API" cmd /c "cd backend && php -S localhost:8000 index.php"

echo [2] Frontend Sunucusu baslatiliyor (Port: 3000)...
start "WR META - Frontend" cmd /c "cd frontend && php -S localhost:3000"

echo.
echo ====================================================
echo   Sunucular calisiyor!
echo   Frontend: http://localhost:3000
echo   Backend API: http://localhost:8000/api/champions
echo ====================================================
echo.
echo Tarayici aciliyor...
timeout /t 2 >nul
start http://localhost:3000

echo Sunuculari kapatmak icin acilan diger komut pencerelerini kapatin.
pause
