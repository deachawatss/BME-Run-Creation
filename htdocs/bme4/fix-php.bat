@echo off
REM =====================================
REM Simple PHP Apache Fix - No Complex Variables
REM =====================================

echo.
echo ========================================  
echo  SIMPLE PHP APACHE MODULE FIX
echo ========================================
echo.

REM Check admin privileges - simple version
net session >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Must run as Administrator
    echo Right-click and select "Run as administrator"
    pause
    exit /b 1
)

echo [OK] Running as Administrator
echo.

REM Step 1: Check PHP installation
echo [STEP 1] Checking PHP installation...
if exist "C:\php\php.exe" (
    echo [OK] PHP found at C:\php\
    C:\php\php.exe -v
) else (
    echo [ERROR] No PHP found at C:\php\
    echo Please run setup-bme4-environment.bat first
    pause
    exit /b 1
)

echo.

REM Step 2: Check Apache module
echo [STEP 2] Checking PHP Apache module...
if exist "C:\php\php8apache2_4.dll" (
    echo [OK] PHP Apache module found
    echo [INFO] Issue might be missing dependencies
    goto CheckDependencies
) else (
    echo [ERROR] PHP Apache module missing
    echo [INFO] You have Non-Thread-Safe PHP, need Thread-Safe version
    goto DownloadThreadSafe
)

:CheckDependencies
echo.
echo [STEP 3] Installing Visual C++ Redistributable...

REM Create downloads directory
if not exist "C:\BME4-Setup\downloads" mkdir "C:\BME4-Setup\downloads"

REM Download and install VC++ Redistributable
echo [INFO] Downloading Visual C++ Redistributable...
curl -L -o "C:\BME4-Setup\downloads\vc_redist.x64.exe" "https://aka.ms/vs/17/release/vc_redist.x64.exe" --connect-timeout 30 --max-time 180 --silent

if exist "C:\BME4-Setup\downloads\vc_redist.x64.exe" (
    echo [OK] Downloaded Visual C++ Redistributable
    echo [INFO] Installing... (this may take a moment)
    C:\BME4-Setup\downloads\vc_redist.x64.exe /quiet /norestart
    echo [OK] Visual C++ Redistributable installed
) else (
    echo [WARNING] Failed to download Visual C++ Redistributable
    echo [INFO] You can download manually from: https://aka.ms/vs/17/release/vc_redist.x64.exe
)

goto TestConfiguration

:DownloadThreadSafe
echo.
echo [STEP 3] Downloading Thread-Safe PHP...

REM Create downloads directory
if not exist "C:\BME4-Setup\downloads" mkdir "C:\BME4-Setup\downloads"

REM Download Thread-Safe PHP
echo [INFO] Downloading Thread-Safe PHP 8.3...
curl -L -o "C:\BME4-Setup\downloads\php-8.3.13-ts.zip" "https://windows.php.net/downloads/releases/php-8.3.13-Win32-vs16-x64.zip" --connect-timeout 30 --max-time 300

if exist "C:\BME4-Setup\downloads\php-8.3.13-ts.zip" (
    echo [OK] Thread-Safe PHP downloaded
    
    echo [INFO] Extracting to C:\php-ts\...
    if exist "C:\php-ts" rmdir /s /q "C:\php-ts"
    powershell -Command "Expand-Archive -Path 'C:\BME4-Setup\downloads\php-8.3.13-ts.zip' -DestinationPath 'C:\php-ts' -Force"
    
    if exist "C:\php-ts\php8apache2_4.dll" (
        echo [OK] Thread-Safe PHP installed with Apache module
        
        REM Copy existing configuration
        if exist "C:\php\php.ini" (
            copy "C:\php\php.ini" "C:\php-ts\php.ini" >nul
            echo [OK] Copied existing PHP configuration
        )
        
        goto UpdateApacheConfig
    ) else (
        echo [ERROR] Apache module still missing after extraction
        goto ManualFix
    )
) else (
    echo [ERROR] Failed to download Thread-Safe PHP
    goto ManualFix
)

:UpdateApacheConfig
echo.
echo [STEP 4] Updating Apache configuration...

REM Backup Apache configuration
echo [INFO] Backing up Apache configuration...
copy "C:\webserver\Apache2\conf\httpd.conf" "C:\webserver\Apache2\conf\httpd.conf.backup.simple" >nul

REM Update PHP paths in Apache configuration
echo [INFO] Updating PHP paths in Apache configuration...
powershell -Command "(Get-Content 'C:\webserver\Apache2\conf\httpd.conf') -replace 'LoadModule php_module \"C:/php/php8apache2_4.dll\"', 'LoadModule php_module \"C:/php-ts/php8apache2_4.dll\"' | Set-Content 'C:\webserver\Apache2\conf\httpd.conf'"
powershell -Command "(Get-Content 'C:\webserver\Apache2\conf\httpd.conf') -replace 'PHPIniDir \"C:/php\"', 'PHPIniDir \"C:/php-ts\"' | Set-Content 'C:\webserver\Apache2\conf\httpd.conf'"

echo [OK] Apache configuration updated

:TestConfiguration
echo.
echo [STEP 5] Testing Apache configuration...

echo [INFO] Testing Apache configuration syntax...
C:\webserver\Apache2\bin\httpd.exe -t 2>nul

if errorlevel 1 (
    echo [ERROR] Apache configuration has issues
    echo [INFO] Showing detailed error:
    C:\webserver\Apache2\bin\httpd.exe -t
    goto ManualFix
) else (
    echo [OK] Apache configuration is valid
    goto Success
)

:Success
echo.
echo ========================================
echo SUCCESS - PHP APACHE MODULE FIXED
echo ========================================
echo.
echo Next steps:
echo 1. Run: deploy-bme4-production.bat
echo 2. Start: start-bme4-production.bat  
echo 3. Test: http://192.168.0.21/bme4/public/
echo.
pause
exit /b 0

:ManualFix
echo.
echo ========================================
echo MANUAL FIX REQUIRED
echo ========================================
echo.
echo The automatic fix didn't work. Try these manual steps:
echo.
echo 1. DOWNLOAD THREAD-SAFE PHP:
echo    - Go to: https://windows.php.net/download/
echo    - Download: PHP 8.3 Thread Safe (TS) x64
echo    - Extract to: C:\php-ts\
echo.
echo 2. UPDATE APACHE CONFIG:
echo    Edit: C:\webserver\Apache2\conf\httpd.conf
echo    Change: LoadModule php_module "C:/php/php8apache2_4.dll"
echo    To:     LoadModule php_module "C:/php-ts/php8apache2_4.dll"
echo    Change: PHPIniDir "C:/php"  
echo    To:     PHPIniDir "C:/php-ts"
echo.
echo 3. INSTALL VISUAL C++ REDISTRIBUTABLE:
echo    - Download from: https://aka.ms/vs/17/release/vc_redist.x64.exe
echo    - Install and restart server
echo.
echo 4. TEST APACHE:
echo    C:\webserver\Apache2\bin\httpd.exe -t
echo.
pause
exit /b 1