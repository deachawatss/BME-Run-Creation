@echo off
REM =====================================
REM BME4 Complete Production Deployment Script
REM For Windows Server 192.168.0.21 with Apache2 at C:\webserver\Apache2\
REM =====================================

echo BME4 Production Deployment Setup
echo.

REM Check if running as Administrator
net session >nul 2>&1
if %errorLevel% == 0 (
    echo [OK] Running as Administrator
) else (
    echo [ERROR] This script must be run as Administrator
    echo Right-click and select "Run as Administrator"
    pause
    exit /b 1
)

REM Set paths for your specific setup
set BME4_ROOT=C:\webserver\htdocs\bme4
set APACHE_ROOT=C:\webserver\Apache2
set APACHE_CONF=%APACHE_ROOT%\conf\httpd.conf
set APACHE_EXTRA=%APACHE_ROOT%\conf\extra

echo.
echo =====================================
echo Deployment Configuration
echo =====================================
echo BME4 Application: %BME4_ROOT%
echo Apache Root: %APACHE_ROOT%
echo Apache Config: %APACHE_CONF%
echo Server IP: 192.168.0.21
echo.

REM Step 1: Verify directories exist
echo [STEP 1] Verifying directory structure...
if not exist "%BME4_ROOT%" (
    echo [ERROR] BME4 directory not found: %BME4_ROOT%
    pause
    exit /b 1
)
echo [OK] BME4 directory found

if not exist "%APACHE_ROOT%" (
    echo [ERROR] Apache directory not found: %APACHE_ROOT%
    pause
    exit /b 1
)
echo [OK] Apache directory found

if not exist "%APACHE_EXTRA%" (
    echo [INFO] Creating Apache extra config directory...
    mkdir "%APACHE_EXTRA%"
)
echo [OK] Apache extra config directory ready

REM Step 2: Create Apache configuration
echo.
echo [STEP 2] Configuring Apache...
echo [INFO] Creating Apache configuration for BME4...

set APACHE_BME4_CONF=%APACHE_EXTRA%\bme4-production.conf

echo # BME4 Production Configuration for Windows Server 192.168.0.21 > "%APACHE_BME4_CONF%"
echo # Created: %date% %time% >> "%APACHE_BME4_CONF%"
echo. >> "%APACHE_BME4_CONF%"
echo ^<VirtualHost *:80^> >> "%APACHE_BME4_CONF%"
echo     ServerName 192.168.0.21 >> "%APACHE_BME4_CONF%"
echo     DocumentRoot "C:/webserver/htdocs/bme4/public" >> "%APACHE_BME4_CONF%"
echo     DirectoryIndex index.php index.html >> "%APACHE_BME4_CONF%"
echo. >> "%APACHE_BME4_CONF%"
echo     ^<Directory "C:/webserver/htdocs/bme4/public"^> >> "%APACHE_BME4_CONF%"
echo         Options -Indexes +FollowSymLinks -MultiViews >> "%APACHE_BME4_CONF%"
echo         AllowOverride All >> "%APACHE_BME4_CONF%"
echo         Require all granted >> "%APACHE_BME4_CONF%"
echo     ^</Directory^> >> "%APACHE_BME4_CONF%"
echo. >> "%APACHE_BME4_CONF%"
echo     # Basic configuration >> "%APACHE_BME4_CONF%"
echo     # Security headers disabled to avoid module conflicts >> "%APACHE_BME4_CONF%"
echo. >> "%APACHE_BME4_CONF%"
echo     # Basic performance settings >> "%APACHE_BME4_CONF%"
echo     # Note: Advanced compression disabled to avoid module conflicts >> "%APACHE_BME4_CONF%"
echo. >> "%APACHE_BME4_CONF%"
echo     # Logging >> "%APACHE_BME4_CONF%"
echo     ErrorLog "C:/webserver/Apache2/logs/bme4_error.log" >> "%APACHE_BME4_CONF%"
echo     CustomLog "C:/webserver/Apache2/logs/bme4_access.log" combined >> "%APACHE_BME4_CONF%"
echo ^</VirtualHost^> >> "%APACHE_BME4_CONF%"

echo [OK] Apache configuration created: %APACHE_BME4_CONF%

REM Step 3: Update httpd.conf
echo.
echo [STEP 3] Updating Apache main configuration...
findstr /C:"Include conf/extra/bme4-production.conf" "%APACHE_CONF%" >nul
if %errorLevel% neq 0 (
    echo. >> "%APACHE_CONF%"
    echo # BME4 Production Configuration >> "%APACHE_CONF%"
    echo Include conf/extra/bme4-production.conf >> "%APACHE_CONF%"
    echo [OK] Added BME4 configuration to httpd.conf
) else (
    echo [INFO] BME4 configuration already exists in httpd.conf
)

REM Step 4: Setup environment file
echo.
echo [STEP 4] Configuring production environment...
if exist "%BME4_ROOT%\.env.production" (
    if not exist "%BME4_ROOT%\.env" (
        copy "%BME4_ROOT%\.env.production" "%BME4_ROOT%\.env"
        echo [OK] Production environment file created
    ) else (
        echo [INFO] Environment file already exists
        echo [WARNING] Please verify database credentials in .env file
    )
) else (
    echo [ERROR] Production environment template not found: %BME4_ROOT%\.env.production
    pause
    exit /b 1
)

REM Step 5: Create required directories
echo.
echo [STEP 5] Creating required directories...
if not exist "C:\webserver\logs" (
    mkdir "C:\webserver\logs"
    echo [OK] Created logs directory
)

if not exist "C:\temp" (
    mkdir "C:\temp"
    echo [OK] Created temp directory
)

if not exist "%BME4_ROOT%\writable\cache" (
    mkdir "%BME4_ROOT%\writable\cache"
    echo [OK] Created cache directory
)

if not exist "%BME4_ROOT%\writable\logs" (
    mkdir "%BME4_ROOT%\writable\logs"
    echo [OK] Created application logs directory
)

if not exist "%BME4_ROOT%\writable\session" (
    mkdir "%BME4_ROOT%\writable\session"
    echo [OK] Created session directory
)

REM Step 6: Set directory permissions
echo.
echo [STEP 6] Setting directory permissions...
icacls "%BME4_ROOT%\writable" /grant "Everyone:(OI)(CI)F" /T >nul 2>&1
icacls "C:\webserver\logs" /grant "Everyone:(OI)(CI)F" /T >nul 2>&1
echo [OK] Directory permissions configured

REM Step 7: Test Apache configuration
echo.
echo [STEP 7] Testing Apache configuration...
"%APACHE_ROOT%\bin\httpd.exe" -t
if %errorLevel% == 0 (
    echo [OK] Apache configuration test passed
) else (
    echo [ERROR] Apache configuration test failed
    echo Please check the configuration and try again
    pause
    exit /b 1
)

REM Step 8: Create desktop shortcuts
echo.
echo [STEP 8] Creating management shortcuts...

REM Create start shortcut
echo Set WshShell = WScript.CreateObject("WScript.Shell") > "%TEMP%\create_start_shortcut.vbs"
echo Set Shortcut = WshShell.CreateShortcut("%USERPROFILE%\Desktop\Start BME4 Production.lnk") >> "%TEMP%\create_start_shortcut.vbs"
echo Shortcut.TargetPath = "%BME4_ROOT%\start-bme4-production.bat" >> "%TEMP%\create_start_shortcut.vbs"
echo Shortcut.WorkingDirectory = "%BME4_ROOT%" >> "%TEMP%\create_start_shortcut.vbs"
echo Shortcut.Description = "Start BME4 Production Server" >> "%TEMP%\create_start_shortcut.vbs"
echo Shortcut.Save >> "%TEMP%\create_start_shortcut.vbs"
cscript //nologo "%TEMP%\create_start_shortcut.vbs"
del "%TEMP%\create_start_shortcut.vbs"

REM Create stop shortcut
echo Set WshShell = WScript.CreateObject("WScript.Shell") > "%TEMP%\create_stop_shortcut.vbs"
echo Set Shortcut = WshShell.CreateShortcut("%USERPROFILE%\Desktop\Stop BME4 Production.lnk") >> "%TEMP%\create_stop_shortcut.vbs"
echo Shortcut.TargetPath = "%BME4_ROOT%\stop-bme4-production.bat" >> "%TEMP%\create_stop_shortcut.vbs"
echo Shortcut.WorkingDirectory = "%BME4_ROOT%" >> "%TEMP%\create_stop_shortcut.vbs"
echo Shortcut.Description = "Stop BME4 Production Server" >> "%TEMP%\create_stop_shortcut.vbs"
echo Shortcut.Save >> "%TEMP%\create_stop_shortcut.vbs"
cscript //nologo "%TEMP%\create_stop_shortcut.vbs"
del "%TEMP%\create_stop_shortcut.vbs"

echo [OK] Desktop shortcuts created

echo.
echo =====================================
echo DEPLOYMENT COMPLETE!
echo =====================================
echo.
echo Next Steps:
echo.
echo 1. CONFIGURE DATABASE CREDENTIALS:
echo    Edit: %BME4_ROOT%\.env
echo    Update the database passwords for TFCMOBILE and TFCPILOT3
echo.
echo 2. START BME4 PRODUCTION:
echo    Double-click: "Start BME4 Production" on your desktop
echo    OR run: %BME4_ROOT%\start-bme4-production.bat
echo.
echo 3. ACCESS BME4:
echo    Main Dashboard: http://192.168.0.21/
echo    Create Run Bulk: http://192.168.0.21/CreateRunBulk
echo    Create Run Partial: http://192.168.0.21/CreateRunPartial
echo.
echo 4. STOP BME4 (when needed):
echo    Double-click: "Stop BME4 Production" on your desktop
echo    OR run: %BME4_ROOT%\stop-bme4-production.bat
echo.
echo Configuration Files:
echo - Apache Config: %APACHE_EXTRA%\bme4-production.conf
echo - Environment: %BME4_ROOT%\.env
echo - PHP Config: %BME4_ROOT%\php-production.ini
echo.
echo Log Files:
echo - Apache Error: %APACHE_ROOT%\logs\bme4_error.log
echo - Apache Access: %APACHE_ROOT%\logs\bme4_access.log
echo - BME4 Application: %BME4_ROOT%\writable\logs\
echo.

REM Ask if user wants to configure database now
echo.
set /p configure_db=Do you want to configure database credentials now? (Y/N): 
if /i "%configure_db%"=="Y" (
    echo.
    echo Opening .env file for database configuration...
    echo Please update the following lines with your actual database credentials:
    echo   database.default.password = your_database_password_here
    echo   database.pilot.password = your_database_password_here
    echo.
    notepad "%BME4_ROOT%\.env"
)

REM Ask if user wants to start BME4 now
echo.
set /p start_now=Do you want to start BME4 Production now? (Y/N): 
if /i "%start_now%"=="Y" (
    echo.
    echo Starting BME4 Production...
    call "%BME4_ROOT%\start-bme4-production.bat"
) else (
    echo.
    echo BME4 is ready to start when you're ready!
    echo Use the desktop shortcuts or run the batch files manually.
)

echo.
echo Press any key to finish...
pause >nul