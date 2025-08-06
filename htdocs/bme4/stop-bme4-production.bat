@echo off
REM =====================================
REM BME4 Production Stop Script for Windows Server 192.168.0.21
REM This script stops Apache and related services for BME4 production
REM =====================================

echo Stopping BME4 Production Environment...
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
set APACHE_ROOT=C:\webserver\Apache2

echo.
echo =====================================
echo BME4 Production Shutdown
echo =====================================
echo Apache Root: %APACHE_ROOT%
echo Server IP: 192.168.0.21
echo.

REM Check if Apache is running
tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo Stopping Apache HTTP Server...
    "%APACHE_ROOT%\bin\httpd.exe" -k stop
    
    REM Wait for graceful shutdown
    timeout /t 5 /nobreak >nul
    
    REM Check if Apache stopped
    tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
    if "%ERRORLEVEL%"=="0" (
        echo Apache is still running - forcing shutdown...
        taskkill /F /IM httpd.exe >nul 2>&1
        timeout /t 2 /nobreak >nul
    )
    
    REM Final check
    tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe">NUL
    if "%ERRORLEVEL%"=="0" (
        echo WARNING: Apache processes may still be running
    ) else (
        echo Apache stopped successfully
    )
) else (
    echo Apache is not running
)

echo.
echo =====================================
echo BME4 Production Stopped
echo =====================================
echo.
echo BME4 is no longer accessible at http://192.168.0.21/
echo To start BME4 again: run start-bme4-production.bat
echo.

echo Press any key to continue...
pause >nul