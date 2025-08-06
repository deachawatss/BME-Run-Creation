@echo off
echo =====================================
echo Complete SQLSRV Fix for PHP 8.3
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

echo Step 1: Check current PHP configuration
echo =====================================
echo.

if not exist "C:\php\php.exe" (
    echo ERROR: PHP not found at C:\php\
    pause
    exit /b 1
)

echo Current PHP: PHP 8.3.24 (ZTS Visual C++ 2019 x64)
echo.

echo Step 2: Download and install prerequisites
echo =====================================
echo.

echo Installing Visual C++ Redistributable 2019 x64...
echo Please download and install from:
echo https://aka.ms/vs/17/release/vc_redist.x64.exe
echo.
echo Press any key after installing Visual C++ Redistributable...
pause >nul

echo.
echo Installing Microsoft ODBC Driver 18 for SQL Server...
echo Please download and install from:
echo https://go.microsoft.com/fwlink/?linkid=2187214
echo.
echo Press any key after installing ODBC Driver...
pause >nul

echo.
echo Step 3: Download correct SQLSRV extensions
echo =====================================
echo.

echo For PHP 8.3 x64 Thread Safe, download:
echo https://github.com/Microsoft/msphpsql/releases/download/v5.12.0/Windows-8.1-5.12.0-8.3-ts-x64.zip
echo.
echo Or use PowerShell to download automatically:
echo.

powershell -Command "& {
    $url = 'https://github.com/Microsoft/msphpsql/releases/download/v5.12.0/Windows-8.1-5.12.0-8.3-ts-x64.zip'
    $output = 'C:\temp\sqlsrv-extensions.zip'
    if (-not (Test-Path 'C:\temp')) { New-Item -ItemType Directory -Path 'C:\temp' }
    Write-Host 'Downloading SQLSRV extensions...'
    try {
        Invoke-WebRequest -Uri $url -OutFile $output -UseBasicParsing
        Write-Host 'Download completed successfully'
        
        Write-Host 'Extracting files...'
        Expand-Archive -Path $output -DestinationPath 'C:\temp\sqlsrv' -Force
        
        Write-Host 'Copying extensions to PHP directory...'
        if (-not (Test-Path 'C:\php\ext')) { New-Item -ItemType Directory -Path 'C:\php\ext' }
        Copy-Item 'C:\temp\sqlsrv\php_sqlsrv.dll' 'C:\php\ext\' -Force
        Copy-Item 'C:\temp\sqlsrv\php_pdo_sqlsrv.dll' 'C:\php\ext\' -Force
        
        Write-Host 'Extensions copied successfully'
    } catch {
        Write-Host 'Error downloading. Please download manually.'
        Write-Host $_.Exception.Message
    }
}"

echo.
echo Press any key to continue after download completes...
pause >nul

echo.
echo Step 4: Verify extension files exist
echo =====================================
echo.

if exist "C:\php\ext\php_sqlsrv.dll" (
    echo ✅ php_sqlsrv.dll found
) else (
    echo ❌ php_sqlsrv.dll NOT found
    echo Please manually copy php_sqlsrv.dll to C:\php\ext\
)

if exist "C:\php\ext\php_pdo_sqlsrv.dll" (
    echo ✅ php_pdo_sqlsrv.dll found
) else (
    echo ❌ php_pdo_sqlsrv.dll NOT found  
    echo Please manually copy php_pdo_sqlsrv.dll to C:\php\ext\
)

echo.
echo Step 5: Clean php.ini and add extensions properly
echo =====================================
echo.

REM Backup php.ini
if exist "C:\php\php.ini" (
    copy "C:\php\php.ini" "C:\php\php.ini.backup.%date:~-4,4%%date:~-10,2%%date:~-7,2%" >nul
    echo ✅ Backed up php.ini
)

REM Remove existing SQLSRV entries
powershell -Command "& {
    $content = Get-Content 'C:\php\php.ini' | Where-Object { $_ -notmatch 'php_sqlsrv.dll' -and $_ -notmatch 'php_pdo_sqlsrv.dll' }
    $content | Set-Content 'C:\php\php.ini'
}"

REM Add extensions in proper location (after other extensions)
powershell -Command "& {
    $content = Get-Content 'C:\php\php.ini'
    $newContent = @()
    $extensionSectionFound = $false
    
    foreach ($line in $content) {
        $newContent += $line
        if ($line -match '^\s*extension\s*=' -and -not $extensionSectionFound) {
            $extensionSectionFound = $true
        }
        if ($extensionSectionFound -and ($line -match '^\s*$' -or $line -match '^\s*;')) {
            $newContent += 'extension=php_sqlsrv.dll'
            $newContent += 'extension=php_pdo_sqlsrv.dll'
            break
        }
    }
    
    if (-not $extensionSectionFound) {
        $newContent += ''
        $newContent += '; SQL Server Extensions'
        $newContent += 'extension=php_sqlsrv.dll'
        $newContent += 'extension=php_pdo_sqlsrv.dll'
    }
    
    $newContent | Set-Content 'C:\php\php.ini'
}"

echo ✅ Updated php.ini configuration

echo.
echo Step 6: Test PHP configuration
echo =====================================
echo.

echo Testing PHP syntax...
C:\php\php.exe -t
if %ERRORLEVEL% neq 0 (
    echo ❌ PHP configuration has errors
    pause >nul
    exit /b 1
)

echo.
echo Step 7: Restart Apache
echo =====================================
echo.

cd C:\webserver\Apache2\bin
echo Stopping Apache...
.\httpd.exe -k stop
timeout /t 3 /nobreak >nul

echo Starting Apache...
.\httpd.exe -k start

echo.
echo Step 8: Final verification
echo =====================================
echo.

echo Checking loaded extensions...
C:\php\php.exe -m | findstr -i sqlsrv
if %ERRORLEVEL% equ 0 (
    echo ✅ SUCCESS: SQLSRV extensions loaded!
    echo.
    echo Testing database connection...
    C:\php\php.exe -r "echo 'Available drivers: '; print_r(PDO::getAvailableDrivers());"
) else (
    echo ❌ SQLSRV extensions still not loaded
    echo.
    echo Troubleshooting steps:
    echo 1. Check if Visual C++ Redistributable 2019 is installed
    echo 2. Check if ODBC Driver 18 is installed  
    echo 3. Verify extension files exist in C:\php\ext\
    echo 4. Check Windows Event Viewer for errors
)

echo.
echo =====================================
echo Fix Complete!
echo =====================================
echo.
echo Now test BME4 at: http://192.168.0.21/
echo.
echo Press any key to exit...
pause >nul