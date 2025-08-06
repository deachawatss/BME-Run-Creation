@echo off
echo =====================================
echo Installing PHP SQLSRV Extensions
echo =====================================
echo.

REM Check if running as administrator
net session >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERROR: This script must be run as Administrator
    echo Right-click and select "Run as Administrator"
    pause
    exit /b 1
)

echo Checking PHP installation...
if not exist "C:\webserver\php\php.exe" (
    echo ERROR: PHP not found at C:\webserver\php\
    echo Please install PHP first
    pause
    exit /b 1
)

echo.
echo Current PHP version:
C:\webserver\php\php.exe -v

echo.
echo =====================================
echo Step 1: Download Microsoft ODBC Driver for SQL Server
echo =====================================
echo Please download and install Microsoft ODBC Driver 17 or 18 for SQL Server from:
echo https://docs.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server
echo.
echo Press any key after installing ODBC Driver...
pause

echo.
echo =====================================
echo Step 2: Download PHP SQLSRV Extensions
echo =====================================
echo.

REM Detect PHP version
for /f "tokens=2 delims= " %%i in ('C:\webserver\php\php.exe -r "echo PHP_VERSION;"') do set PHP_VERSION=%%i
echo Detected PHP Version: %PHP_VERSION%

echo.
echo Download the appropriate SQLSRV extensions from:
echo https://github.com/Microsoft/msphpsql/releases/latest
echo.
echo For PHP 8.3, download: Microsoft-Drivers-5.12.0-for-PHP-for-SQLServer-Win32-ETS.zip
echo Extract and copy these files to C:\webserver\php\ext\:
echo - php_sqlsrv.dll
echo - php_pdo_sqlsrv.dll
echo.
echo Press any key after downloading and copying the extensions...
pause

echo.
echo =====================================
echo Step 3: Update php.ini Configuration
echo =====================================
echo.

REM Backup current php.ini
if exist "C:\webserver\php\php.ini" (
    copy "C:\webserver\php\php.ini" "C:\webserver\php\php.ini.backup.%date:~-4,4%%date:~-10,2%%date:~-7,2%"
    echo ✅ Backed up php.ini
)

REM Add SQLSRV extensions to php.ini
echo Adding SQLSRV extensions to php.ini...

REM Check if extensions already exist
findstr /C:"php_sqlsrv.dll" "C:\webserver\php\php.ini" >nul
if %ERRORLEVEL% neq 0 (
    echo extension=php_sqlsrv.dll >> "C:\webserver\php\php.ini"
    echo ✅ Added php_sqlsrv.dll
) else (
    echo ⚠️ php_sqlsrv.dll already in php.ini
)

findstr /C:"php_pdo_sqlsrv.dll" "C:\webserver\php\php.ini" >nul
if %ERRORLEVEL% neq 0 (
    echo extension=php_pdo_sqlsrv.dll >> "C:\webserver\php\php.ini"
    echo ✅ Added php_pdo_sqlsrv.dll
) else (
    echo ⚠️ php_pdo_sqlsrv.dll already in php.ini
)

echo.
echo =====================================
echo Step 4: Restart Apache Server
echo =====================================
echo.

echo Stopping Apache...
cd C:\webserver\Apache2\bin
.\httpd.exe -k stop

echo Waiting 3 seconds...
timeout /t 3 /nobreak >nul

echo Starting Apache...
.\httpd.exe -k start

echo.
echo =====================================
echo Step 5: Verify Installation
echo =====================================
echo.

echo Checking loaded PHP extensions...
C:\webserver\php\php.exe -m | findstr -i sqlsrv
if %ERRORLEVEL% equ 0 (
    echo ✅ SQLSRV extensions loaded successfully!
) else (
    echo ❌ SQLSRV extensions not loaded. Check installation.
    echo.
    echo Common issues:
    echo 1. ODBC Driver not installed
    echo 2. Wrong PHP version extensions
    echo 3. Missing Visual C++ Redistributable
    echo 4. Extensions not in C:\webserver\php\ext\ directory
)

echo.
echo =====================================
echo Installation Complete!
echo =====================================
echo.
echo Next steps:
echo 1. Test BME4 at: http://192.168.0.21/
echo 2. Check Apache error logs if issues persist
echo 3. Run verify-bme4.bat for full system check
echo.
pause