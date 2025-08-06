@echo off
REM =====================================
REM BME4 Environment Verification
REM Quick verification and troubleshooting tool
REM =====================================

setlocal EnableDelayedExpansion

echo.
echo =====================================
echo BME4 ENVIRONMENT VERIFICATION
echo =====================================
echo.

REM Configuration
set PHP_PATH=C:\php
set REDIS_PATH=C:\redis
set APACHE_ROOT=C:\webserver\Apache2
set BME4_ROOT=C:\webserver\htdocs\bme4

REM Initialize counters
set TESTS_TOTAL=0
set TESTS_PASSED=0
set TESTS_WARNINGS=0

echo Server IP: 192.168.0.21
echo Current Time: %date% %time%
echo.

echo =====================================
echo CORE COMPONENTS VERIFICATION
echo =====================================

REM Test 1: PHP Installation
call :TestComponent "PHP 8.3 Installation" "%PHP_PATH%\php.exe"
if exist "%PHP_PATH%\php.exe" (
    for /f "tokens=2" %%v in ('"%PHP_PATH%\php.exe" -v ^| find "PHP"') do (
        echo   Version: %%v
    )
    
    REM Test PHP extensions
    call :TestPHPExtension "mbstring"
    call :TestPHPExtension "intl" 
    call :TestPHPExtension "curl"
    call :TestPHPExtension "openssl"
    call :TestPHPExtension "json"
    
    REM Test SQL Server extensions
    call :TestPHPExtension "sqlsrv"
    call :TestPHPExtension "pdo_sqlsrv"
    
    REM Test OPcache
    "%PHP_PATH%\php.exe" -m | find "Zend OPcache" >nul
    if !errorlevel! == 0 (
        echo   ✓ OPcache: ENABLED
    ) else (
        echo   ⚠ OPcache: DISABLED (performance impact)
        set /a TESTS_WARNINGS+=1
    )
)

echo.

REM Test 2: Apache Web Server
call :TestComponent "Apache Web Server" "%APACHE_ROOT%\bin\httpd.exe"
if exist "%APACHE_ROOT%\bin\httpd.exe" (
    "%APACHE_ROOT%\bin\httpd.exe" -v | find "Apache" 
    
    REM Test Apache configuration
    "%APACHE_ROOT%\bin\httpd.exe" -t >nul 2>&1
    if !errorlevel! == 0 (
        echo   ✓ Configuration: VALID
    ) else (
        echo   ✗ Configuration: INVALID
        set /a TESTS_WARNINGS+=1
    )
    
    REM Test if Apache is running
    tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe" >NUL
    if !errorlevel! == 0 (
        echo   ✓ Service Status: RUNNING
    ) else (
        echo   ⚠ Service Status: NOT RUNNING
        set /a TESTS_WARNINGS+=1
    )
    
    REM Test PHP integration
    findstr /C:"LoadModule php" "%APACHE_ROOT%\conf\httpd.conf" >nul
    if !errorlevel! == 0 (
        echo   ✓ PHP Module: CONFIGURED
    ) else (
        echo   ✗ PHP Module: NOT CONFIGURED
        set /a TESTS_WARNINGS+=1
    )
)

echo.

REM Test 3: Redis Server
call :TestComponent "Redis Server" "%REDIS_PATH%\redis-server.exe"
if exist "%REDIS_PATH%\redis-server.exe" (
    REM Test Redis service
    sc query Redis >nul 2>&1
    if !errorlevel! == 0 (
        sc query Redis | find "RUNNING" >nul
        if !errorlevel! == 0 (
            echo   ✓ Service Status: RUNNING
        ) else (
            echo   ⚠ Service Status: INSTALLED BUT NOT RUNNING
            set /a TESTS_WARNINGS+=1
        )
    ) else (
        echo   ⚠ Service Status: NOT INSTALLED AS SERVICE
        set /a TESTS_WARNINGS+=1
    )
    
    REM Test Redis connectivity
    if exist "%REDIS_PATH%\redis-cli.exe" (
        echo PING | "%REDIS_PATH%\redis-cli.exe" -p 6379 2>nul | find "PONG" >nul
        if !errorlevel! == 0 (
            echo   ✓ Connectivity: RESPONDING
        ) else (
            echo   ⚠ Connectivity: NOT RESPONDING
            set /a TESTS_WARNINGS+=1
        )
    )
)

echo.

REM Test 4: BME4 Application
call :TestComponent "BME4 Application" "%BME4_ROOT%\public\index.php"
if exist "%BME4_ROOT%\public\index.php" (
    REM Test environment configuration
    if exist "%BME4_ROOT%\.env" (
        echo   ✓ Environment Config: FOUND
        
        REM Check database configuration
        findstr /C:"database.default.hostname" "%BME4_ROOT%\.env" >nul
        if !errorlevel! == 0 (
            echo   ✓ Database Config: CONFIGURED
        ) else (
            echo   ⚠ Database Config: CHECK NEEDED
            set /a TESTS_WARNINGS+=1
        )
    ) else (
        echo   ✗ Environment Config: MISSING
        set /a TESTS_WARNINGS+=1
    )
    
    REM Test writable directories
    if exist "%BME4_ROOT%\writable" (
        echo test > "%BME4_ROOT%\writable\test.tmp" 2>nul
        if exist "%BME4_ROOT%\writable\test.tmp" (
            del "%BME4_ROOT%\writable\test.tmp" 2>nul
            echo   ✓ Directory Permissions: WRITABLE
        ) else (
            echo   ✗ Directory Permissions: NOT WRITABLE
            set /a TESTS_WARNINGS+=1
        )
    ) else (
        echo   ✗ Writable Directory: NOT FOUND
        set /a TESTS_WARNINGS+=1
    )
    
    REM Test Composer dependencies
    if exist "%BME4_ROOT%\vendor\autoload.php" (
        echo   ✓ Dependencies: INSTALLED
    ) else (
        echo   ⚠ Dependencies: NOT INSTALLED
        echo     Run: cd %BME4_ROOT% ^&^& composer install
        set /a TESTS_WARNINGS+=1
    )
)

echo.
echo =====================================
echo NETWORK CONNECTIVITY
echo =====================================

REM Test port availability
netstat -an | find ":80 " | find "LISTENING" >nul
if !errorlevel! == 0 (
    echo ✓ Port 80: AVAILABLE FOR HTTP
    set /a TESTS_PASSED+=1
) else (
    echo ⚠ Port 80: MAY NOT BE AVAILABLE
    set /a TESTS_WARNINGS+=1
)
set /a TESTS_TOTAL+=1

REM Test server IP
ping -n 1 192.168.0.21 >nul 2>&1
if !errorlevel! == 0 (
    echo ✓ Server IP: 192.168.0.21 REACHABLE
    set /a TESTS_PASSED+=1
) else (
    echo ⚠ Server IP: 192.168.0.21 CHECK NETWORK CONFIG
    set /a TESTS_WARNINGS+=1
)
set /a TESTS_TOTAL+=1

echo.
echo =====================================
echo VERIFICATION SUMMARY
echo =====================================

set /a SUCCESS_RATE=(%TESTS_PASSED% * 100) / %TESTS_TOTAL%
echo.
echo Test Results:
echo   Total Tests: %TESTS_TOTAL%
echo   Passed: %TESTS_PASSED%
echo   Warnings: %TESTS_WARNINGS%
echo   Success Rate: %SUCCESS_RATE%%%
echo.

if %SUCCESS_RATE% GEQ 90 (
    echo [EXCELLENT] ✓ BME4 environment is ready!
    echo.
    echo Access your application:
    echo   BME4 App: http://192.168.0.21/
    echo   PHP Info: http://192.168.0.21/phpinfo.php
) else if %SUCCESS_RATE% GEQ 70 (
    echo [GOOD] ✓ BME4 environment is mostly ready
    echo Some optional components have warnings but core functionality should work.
) else (
    echo [ATTENTION NEEDED] ⚠ Environment has critical issues
    echo Please resolve the failed tests before using BME4.
)

if %TESTS_WARNINGS% GTR 0 (
    echo.
    echo [INFO] %TESTS_WARNINGS% warnings found - these may affect performance but won't prevent basic functionality.
)

echo.
echo =====================================
echo QUICK FIXES
echo =====================================
echo.

REM Suggest fixes based on what failed
if not exist "%PHP_PATH%\php.exe" (
    echo ▶ Install PHP: Run setup-bme4-environment.bat
)

tasklist /FI "IMAGENAME eq httpd.exe" 2>NUL | find /I /N "httpd.exe" >NUL
if !errorlevel! neq 0 (
    echo ▶ Start Apache: %APACHE_ROOT%\bin\httpd.exe -k start
)

sc query Redis | find "RUNNING" >nul 2>&1
if !errorlevel! neq 0 (
    echo ▶ Start Redis: net start Redis
)

if not exist "%BME4_ROOT%\.env" (
    echo ▶ Create .env: copy %BME4_ROOT%\.env.production %BME4_ROOT%\.env
)

echo.
pause
goto :EOF

REM =====================================
REM HELPER FUNCTIONS
REM =====================================

:TestComponent
set COMPONENT_NAME=%~1
set COMPONENT_PATH=%~2
echo [TEST] %COMPONENT_NAME%
set /a TESTS_TOTAL+=1

if exist "%COMPONENT_PATH%" (
    echo ✓ %COMPONENT_NAME%: INSTALLED
    set /a TESTS_PASSED+=1
) else (
    echo ✗ %COMPONENT_NAME%: NOT FOUND
    echo   Expected at: %COMPONENT_PATH%
)
goto :EOF

:TestPHPExtension
set EXT_NAME=%~1
"%PHP_PATH%\php.exe" -m | find "%EXT_NAME%" >nul
if !errorlevel! == 0 (
    echo   ✓ %EXT_NAME%: LOADED
) else (
    echo   ⚠ %EXT_NAME%: NOT LOADED
    set /a TESTS_WARNINGS+=1
)
goto :EOF