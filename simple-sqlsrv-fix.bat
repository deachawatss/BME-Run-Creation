@echo off
setlocal enabledelayedexpansion

echo =====================================
echo Simple SQLSRV Fix for PHP 8.3
echo =====================================
echo.

REM Admin check
net session >nul 2>&1
if !ERRORLEVEL! neq 0 (
    echo ERROR: Run as Administrator
    echo Right-click this file and select "Run as Administrator"
    echo.
    echo Press any key to exit...
    pause >nul
    exit /b 1
)

echo Step 1: Check PHP
echo =================
if not exist "C:\webserver\php\php.exe" (
    echo ERROR: PHP not found at C:\webserver\php\
    echo Install PHP 8.3 first
    echo.
    echo Press any key to exit...
    pause >nul
    exit /b 1
)

echo ✅ PHP found: C:\webserver\php\php.exe
echo.

echo Step 2: Create extension directory
echo ==================================
if not exist "C:\webserver\php\ext\" mkdir "C:\webserver\php\ext"
echo ✅ Extension directory ready
echo.

echo Step 3: Manual Steps Required
echo =============================
echo.
echo PLEASE DO THESE STEPS MANUALLY:
echo.
echo 1. Download Visual C++ 2019 x64:
echo    https://aka.ms/vs/17/release/vc_redist.x64.exe
echo    Install it and restart computer if prompted
echo.
echo 2. Download ODBC Driver 18:
echo    https://go.microsoft.com/fwlink/?linkid=2187214
echo    Install it
echo.
echo 3. Download PHP 8.3 SQLSRV Extensions:
echo    https://github.com/Microsoft/msphpsql/releases/download/v5.12.0/Windows-8.1-5.12.0-8.3-ts-x64.zip
echo.
echo 4. Extract the ZIP file and copy these 2 files to C:\webserver\php\ext\:
echo    - php_sqlsrv.dll
echo    - php_pdo_sqlsrv.dll
echo.
echo Type 'done' when you've completed all steps above:
set /p answer=Enter 'done': 

if /i "!answer!" neq "done" (
    echo Please complete the steps above first
    goto :end
)

echo.
echo Step 4: Check files exist
echo =========================
if not exist "C:\webserver\php\ext\php_sqlsrv.dll" (
    echo ❌ php_sqlsrv.dll not found in C:\webserver\php\ext\
    echo Please copy the file and run this script again
    goto :end
)

if not exist "C:\webserver\php\ext\php_pdo_sqlsrv.dll" (
    echo ❌ php_pdo_sqlsrv.dll not found in C:\webserver\php\ext\
    echo Please copy the file and run this script again
    goto :end
)

echo ✅ Both extension files found
echo.

echo Step 5: Update php.ini
echo ======================
if exist "C:\webserver\php\php.ini" (
    copy "C:\webserver\php\php.ini" "C:\webserver\php\php.ini.backup" >nul
    echo ✅ Backed up php.ini
) else (
    echo Creating php.ini from php.ini-production...
    copy "C:\webserver\php\php.ini-production" "C:\webserver\php\php.ini" >nul
)

REM Add extensions to php.ini
findstr /C:"php_sqlsrv.dll" "C:\webserver\php\php.ini" >nul
if !ERRORLEVEL! neq 0 (
    echo extension=php_sqlsrv.dll >> "C:\webserver\php\php.ini"
    echo ✅ Added php_sqlsrv.dll to php.ini
)

findstr /C:"php_pdo_sqlsrv.dll" "C:\webserver\php\php.ini" >nul
if !ERRORLEVEL! neq 0 (
    echo extension=php_pdo_sqlsrv.dll >> "C:\webserver\php\php.ini"
    echo ✅ Added php_pdo_sqlsrv.dll to php.ini
)

echo.
echo Step 6: Restart Apache
echo ======================
cd /d "C:\webserver\Apache2\bin"
echo Stopping Apache...
httpd.exe -k stop 2>nul
timeout /t 2 /nobreak >nul

echo Starting Apache...
httpd.exe -k start
echo ✅ Apache restarted
echo.

echo Step 7: Test extensions
echo =======================
echo Checking loaded PHP extensions...
C:\webserver\php\php.exe -m | findstr -i sqlsrv
if !ERRORLEVEL! equ 0 (
    echo.
    echo ✅ SUCCESS! SQLSRV extensions are now loaded
    echo ✅ You can now test BME4 at: http://192.168.0.21/
) else (
    echo.
    echo ❌ Extensions still not loading
    echo.
    echo Troubleshooting:
    echo 1. Make sure Visual C++ 2019 x64 is installed
    echo 2. Make sure ODBC Driver 18 is installed
    echo 3. Check Windows Event Viewer for error details
    echo 4. Try restarting the computer
)

:end
echo.
echo =====================================
echo Script Complete
echo =====================================
echo.
echo Press any key to exit...
pause >nul