@echo off
echo =====================================
echo BME4 Environment Setup
echo =====================================

echo.
echo Choose your environment:
echo 1. Development (localhost:8080)
echo 2. Production (192.168.0.21)
echo.

set /p choice="Enter choice (1 or 2): "

if "%choice%"=="1" (
    echo Setting up DEVELOPMENT environment...
    copy /Y C:\webserver\htdocs\bme4\.env.development C:\webserver\htdocs\bme4\.env
    echo ✅ Development configuration activated
    echo ✅ Base URL: http://localhost:8080/
    echo ✅ Database: Development settings
) else if "%choice%"=="2" (
    echo Setting up PRODUCTION environment...
    copy /Y C:\webserver\htdocs\bme4\.env.production C:\webserver\htdocs\bme4\.env
    echo ✅ Production configuration activated
    echo ✅ Base URL: http://192.168.0.21/
    echo ✅ Database: Production TFCMOBILE/TFCPILOT3
) else (
    echo Invalid choice. Please run again and choose 1 or 2.
    pause
    exit /b 1
)

echo.
echo Restarting Apache to apply changes...
cd C:\webserver\Apache2\bin
.\httpd.exe -k restart

echo.
echo =====================================
echo Environment Setup Complete!
echo =====================================
echo.

if "%choice%"=="1" (
    echo Access BME4 at: http://localhost:8080/
) else (
    echo Access BME4 at: http://192.168.0.21/
)

echo.
pause