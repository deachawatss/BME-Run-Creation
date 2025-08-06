@echo off
echo =====================================
echo Fix Apache PHP Configuration
echo =====================================

echo Checking current PHP configuration for Apache...
echo.

echo Step 1: Create phpinfo test file...
echo ^<?php phpinfo(); ?^> > C:\webserver\htdocs\phpinfo.php

echo Step 2: Copy correct php.ini to PHP directory...
copy /Y C:\webserver\php\php.ini C:\php\php.ini

echo Step 3: Verify extension directory in php.ini...
findstr /n "extension_dir" C:\php\php.ini

echo Step 4: Verify intl extension is enabled...
findstr /n "extension=intl" C:\php\php.ini

echo Step 5: Check ICU libraries exist...
dir C:\php\icu*.dll

echo Step 6: Restart Apache to load new configuration...
cd C:\webserver\Apache2\bin
.\httpd.exe -k restart

echo.
echo =====================================
echo Testing Configuration
echo =====================================
echo.
echo Open browser and test:
echo 1. http://192.168.0.21/phpinfo.php - Check extensions
echo 2. http://192.168.0.21/ - Test BME4
echo.

echo If intl still not loaded in phpinfo, try:
echo 1. Add C:\php to Windows system PATH
echo 2. Copy C:\php\icu*.dll to C:\webserver\Apache2\bin\
echo 3. Restart Apache again

pause