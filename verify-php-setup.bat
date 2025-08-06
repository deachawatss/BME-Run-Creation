@echo off
echo =====================================
echo PHP Setup Verification
echo =====================================
echo.

echo Checking PHP Installation...
if exist "C:\webserver\php\php.exe" (
    echo ✅ PHP executable found
    echo.
    C:\webserver\php\php.exe -v
) else (
    echo ❌ PHP executable not found at C:\webserver\php\
    goto :fail
)

echo.
echo Checking PHP Configuration...
C:\webserver\php\php.exe -t
if %ERRORLEVEL% neq 0 (
    echo ❌ PHP configuration has errors
    goto :fail
)

echo.
echo Checking Required Extensions...
echo.
echo Core Extensions:
C:\webserver\php\php.exe -m | findstr -i "intl mbstring curl openssl"

echo.
echo SQLSRV Extensions:
C:\webserver\php\php.exe -m | findstr -i "sqlsrv"
if %ERRORLEVEL% equ 0 (
    echo ✅ SQLSRV extensions loaded
) else (
    echo ⚠️ SQLSRV extensions not loaded
    echo This is expected if setup-complete-php.bat hasn't been run yet
)

echo.
echo Checking Apache Status...
sc query "Apache2.4" | findstr STATE
if %ERRORLEVEL% equ 0 (
    echo ✅ Apache service found
) else (
    echo ⚠️ Apache service not found or not running
)

echo.
echo Checking File Structure...
if exist "C:\webserver\php\php.ini" (
    echo ✅ php.ini found
) else (
    echo ❌ php.ini not found
)

if exist "C:\webserver\php\ext\" (
    echo ✅ Extensions directory found
    echo.
    echo Extensions in directory:
    dir "C:\webserver\php\ext\*sqlsrv*.dll" 2>nul
) else (
    echo ❌ Extensions directory not found
)

echo.
echo Checking BME4 Application...
if exist "C:\webserver\htdocs\bme4\public\index.php" (
    echo ✅ BME4 application found
) else (
    echo ❌ BME4 application not found
)

if exist "C:\webserver\htdocs\bme4\.env" (
    echo ✅ BME4 environment file found
) else (
    echo ❌ BME4 environment file not found
)

echo.
echo =====================================
echo Verification Complete
echo =====================================
echo.
echo Next Steps:
echo 1. If SQLSRV extensions are missing, run: setup-complete-php.bat
echo 2. Test PHP: http://192.168.0.21/phpinfo.php
echo 3. Test BME4: http://192.168.0.21/
echo.
goto :end

:fail
echo.
echo =====================================
echo Setup Issues Detected
echo =====================================
echo.
echo Please run: setup-complete-php.bat
echo.

:end
echo Press any key to exit...
pause >nul