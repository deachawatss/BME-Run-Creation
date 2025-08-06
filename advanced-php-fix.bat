@echo off
echo =====================================
echo Advanced PHP Extension Fix
echo =====================================

echo This script fixes common PHP extension loading issues for Apache

echo Step 1: Copy ICU libraries to Apache bin directory...
echo (Required for intl extension)
copy /Y C:\php\icu*.dll C:\webserver\Apache2\bin\

echo Step 2: Copy all required PHP DLLs to Apache bin...
copy /Y C:\php\php8ts.dll C:\webserver\Apache2\bin\
copy /Y C:\php\libcrypto-3-x64.dll C:\webserver\Apache2\bin\
copy /Y C:\php\libssl-3-x64.dll C:\webserver\Apache2\bin\

echo Step 3: Update php.ini with full paths...
echo Creating backup of current php.ini...
copy C:\php\php.ini C:\php\php.ini.backup

echo Step 4: Set extension directory with full path...
powershell -Command "(Get-Content C:\php\php.ini) -replace ';?extension_dir.*', 'extension_dir = \"C:\php\ext\"' | Set-Content C:\php\php.ini"

echo Step 5: Enable extensions with explicit settings...
powershell -Command "(Get-Content C:\php\php.ini) -replace ';extension=intl', 'extension=intl' | Set-Content C:\php\php.ini"
powershell -Command "(Get-Content C:\php\php.ini) -replace ';extension=mbstring', 'extension=mbstring' | Set-Content C:\php\php.ini"

echo Step 6: Restart Apache service...
cd C:\webserver\Apache2\bin
.\httpd.exe -k restart

echo.
echo =====================================
echo Verification
echo =====================================
echo.
echo Test these URLs:
echo 1. http://192.168.0.21/phpinfo.php - Check if intl appears
echo 2. http://192.168.0.21/ - Test BME4 application
echo.

echo If still not working, check:
echo - Windows system PATH includes C:\php
echo - All ICU DLL files copied to Apache bin directory
echo - No conflicts with multiple PHP installations

pause