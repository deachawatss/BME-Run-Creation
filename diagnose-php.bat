@echo off
echo =====================================
echo PHP SQLSRV Diagnostic Tool
echo =====================================
echo.

echo Checking PHP installation...
if exist "C:\php\php.exe" (
    echo ✅ PHP found at C:\php\
    echo.
    echo PHP Version:
    C:\php\php.exe -v
    echo.
) else (
    echo ❌ PHP not found at C:\php\
    echo Please check PHP installation path
    goto :end
)

echo =====================================
echo Loaded Extensions:
echo =====================================
C:\php\php.exe -m

echo.
echo =====================================
echo SQLSRV Extension Check:
echo =====================================
C:\php\php.exe -m | findstr -i sqlsrv
if %ERRORLEVEL% eql 0 (
    echo ✅ SQLSRV extensions are loaded
) else (
    echo ❌ SQLSRV extensions NOT loaded
    echo.
    echo Available extensions in C:\php\ext\:
    if exist "C:\php\ext\" (
        dir C:\php\ext\*sqlsrv*.dll
    ) else (
        echo ❌ C:\php\ext\ directory not found
    )
)

echo.
echo =====================================
echo php.ini Configuration:
echo =====================================
if exist "C:\php\php.ini" (
    echo Current php.ini location: C:\php\php.ini
    echo.
    echo SQLSRV extension entries:
    findstr /C:"sqlsrv" "C:\php\php.ini"
    if %ERRORLEVEL% neq 0 (
        echo ❌ No SQLSRV extensions found in php.ini
    )
) else (
    echo ❌ php.ini not found at C:\php\php.ini
)

echo.
echo =====================================
echo Apache Error Log (last 20 lines):
echo =====================================
if exist "C:\webserver\Apache2\logs\error.log" (
    powershell "Get-Content 'C:\webserver\Apache2\logs\error.log' -Tail 20"
) else (
    echo ❌ Apache error log not found
)

:end
echo.
echo =====================================
echo Diagnostic Complete
echo =====================================
echo.
echo If SQLSRV extensions are missing, run: install-php-sqlsrv.bat
echo.
pause