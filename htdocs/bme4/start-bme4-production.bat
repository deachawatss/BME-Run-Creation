@echo off
REM =====================================
REM BME4 Production Startup Script for Windows Server 192.168.0.21
REM This script starts Apache and related services for BME4 production
REM =====================================

echo Starting BME4 Production Environment...
echo.

REM Check if running as Administrator
net session >nul 2>&1
if %errorLevel% == 0 (
    echo Running as Administrator - OK
) else (
    echo ERROR: This script must be run as Administrator
    echo Right-click and select "Run as Administrator"
    pause
    exit /b 1
)

REM Set environment variables for your specific setup
set BME4_ROOT=C:\webserver\htdocs\bme4
set APACHE_ROOT=C:\webserver\Apache2
set PHP_ROOT=C:\php

echo.
echo =====================================
echo BME4 Production Startup
echo =====================================
echo BME4 Root: %BME4_ROOT%
echo Apache Root: %APACHE_ROOT%
echo PHP Root: %PHP_ROOT%
echo Server IP: 192.168.0.21
echo.

REM Check if Apache directory exists
if not exist "%APACHE_ROOT%" (
    echo ERROR: Apache directory not found at %APACHE_ROOT%
    echo Please verify your Apache2 installation path
    pause
    exit /b 1
)

REM Check if BME4 directory exists
if not exist "%BME4_ROOT%" (
    echo ERROR: BME4 directory not found at %BME4_ROOT%
    echo Please verify your BME4 installation path
    pause
    exit /b 1
)

REM Copy production environment file if it doesn't exist
if not exist "%BME4_ROOT%\.env" (
    echo Copying production environment configuration...
    if exist "%BME4_ROOT%\.env.production" (
        copy "%BME4_ROOT%\.env.production" "%BME4_ROOT%\.env"
        echo Production environment configured
    ) else (
        echo WARNING: .env.production file not found
        echo Please configure your database credentials manually
    )
)

REM Check if Apache configuration includes BME4
findstr /C:"bme4-production.conf" "%APACHE_ROOT%\conf\httpd.conf" >nul
if %errorLevel% neq 0 (
    echo.
    echo WARNING: BME4 configuration not found in httpd.conf
    echo Please add the following line to %APACHE_ROOT%\conf\httpd.conf:
    echo Include conf/extra/bme4-production.conf
    echo.
)

REM Copy Apache configuration if needed
if not exist "%APACHE_ROOT%\conf\extra\bme4-production.conf" (
    echo Copying Apache configuration...
    if exist "%BME4_ROOT%\apache-production-windows.conf" (
        copy "%BME4_ROOT%\apache-production-windows.conf" "%APACHE_ROOT%\conf\extra\bme4-production.conf"
        echo Apache configuration copied
    ) else (
        echo ERROR: apache-production-windows.conf not found in BME4 directory
    )
)

REM Create necessary directories
echo Creating required directories...
if not exist "C:\webserver\logs" mkdir "C:\webserver\logs"
if not exist "C:\temp" mkdir "C:\temp"
if not exist "%BME4_ROOT%\writable\cache" mkdir "%BME4_ROOT%\writable\cache"
if not exist "%BME4_ROOT%\writable\logs" mkdir "%BME4_ROOT%\writable\logs"
if not exist "%BME4_ROOT%\writable\session" mkdir "%BME4_ROOT%\writable\session"

REM Set permissions for writable directories
echo Setting directory permissions...
icacls "%BME4_ROOT%\writable" /grant "IIS_IUSRS:(OI)(CI)F" /T >nul 2>&1
icacls "C:\webserver\logs" /grant "IIS_IUSRS:(OI)(CI)F" /T >nul 2>&1

REM Check if Apache is running
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo Apache is already running - restarting...
    "%APACHE_ROOT%\bin\httpd.exe" -k stop
    timeout /t 3 /nobreak >nul
)

REM Start Apache
echo Starting Apache HTTP Server...
"%APACHE_ROOT%\bin\httpd.exe" -k start

REM Wait a moment for Apache to start
timeout /t 3 /nobreak >nul

REM Check if Apache started successfully
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo.
    echo =====================================
    echo SUCCESS: BME4 Production Started!
    echo =====================================
    echo.
    echo Access BME4 at: http://192.168.0.21/
    echo.
    echo Dashboard: http://192.168.0.21/
    echo Create Run Bulk: http://192.168.0.21/CreateRunBulk
    echo Create Run Partial: http://192.168.0.21/CreateRunPartial
    echo.
    echo Log files location:
    echo - Apache Error: %APACHE_ROOT%\logs\bme4_error.log
    echo - Apache Access: %APACHE_ROOT%\logs\bme4_access.log
    echo - BME4 Application: %BME4_ROOT%\writable\logs\
    echo.
    echo To stop BME4: run stop-bme4-production.bat
    echo.
) else (
    echo.
    echo ERROR: Apache failed to start
    echo Check the error log: %APACHE_ROOT%\logs\error.log
    echo.
    REM Test Apache configuration
    echo Testing Apache configuration...
    "%APACHE_ROOT%\bin\httpd.exe" -t
)

echo Press any key to continue...
pause >nul