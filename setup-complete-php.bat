@echo off
echo =====================================
echo Complete PHP 8.3 Setup for Webserver
echo =====================================
echo.

REM Check if running as administrator
net session >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ERROR: This script must be run as Administrator
    echo Right-click and select "Run as Administrator"
    pause >nul
    exit /b 1
)

echo Step 1: Create PHP Directory Structure
echo ======================================
if not exist "C:\webserver\php\" mkdir "C:\webserver\php"
if not exist "C:\webserver\php\ext\" mkdir "C:\webserver\php\ext"
if not exist "C:\webserver\temp\" mkdir "C:\webserver\temp"
echo ✅ Directory structure created
echo.

echo Step 2: Download PHP 8.3.24 Thread Safe x64
echo =============================================
echo.
echo Downloading PHP 8.3.24 TS x64 (this may take a few minutes)...

powershell -Command "& {
    $ErrorActionPreference = 'Stop'
    try {
        Write-Host 'Downloading PHP 8.3.24 Thread Safe x64...'
        $url = 'https://windows.php.net/downloads/releases/php-8.3.24-Win32-vs16-x64.zip'
        $output = 'C:\webserver\temp\php-8.3.24.zip'
        
        # Download with progress
        $webClient = New-Object System.Net.WebClient
        Register-ObjectEvent -InputObject $webClient -EventName DownloadProgressChanged -Action {
            $percent = $Event.SourceEventArgs.ProgressPercentage
            Write-Progress -Activity 'Downloading PHP' -Status \"$percent% Complete\" -PercentComplete $percent
        }
        $webClient.DownloadFile($url, $output)
        $webClient.Dispose()
        
        Write-Host 'Download completed successfully'
        
        Write-Host 'Extracting PHP files...'
        Expand-Archive -Path $output -DestinationPath 'C:\webserver\php' -Force
        
        Write-Host 'PHP extraction completed'
        
    } catch {
        Write-Host 'Error downloading PHP: ' $_.Exception.Message
        Write-Host 'Please download manually from: https://windows.php.net/downloads/releases/'
        exit 1
    }
}"

if %ERRORLEVEL% neq 0 (
    echo ❌ PHP download failed
    echo Please download PHP 8.3.24 TS x64 manually from:
    echo https://windows.php.net/downloads/releases/php-8.3.24-Win32-vs16-x64.zip
    echo Extract to C:\webserver\php\
    pause >nul
    exit /b 1
)

echo ✅ PHP 8.3.24 installed successfully
echo.

echo Step 3: Create php.ini Configuration
echo ====================================
if exist "C:\webserver\php\php.ini-production" (
    copy "C:\webserver\php\php.ini-production" "C:\webserver\php\php.ini" >nul
    echo ✅ Created php.ini from production template
) else (
    echo ❌ php.ini-production not found, creating basic php.ini
    echo ; Basic PHP Configuration > "C:\webserver\php\php.ini"
    echo extension_dir = "ext" >> "C:\webserver\php\php.ini"
)

REM Enable common extensions
echo.
echo Enabling common PHP extensions...
(
    echo.
    echo ; Common Extensions
    echo extension=curl
    echo extension=fileinfo
    echo extension=gd
    echo extension=intl
    echo extension=mbstring
    echo extension=openssl
    echo extension=pdo_mysql
    echo extension=mysqli
    echo extension=zip
    echo.
    echo ; SQLSRV Extensions ^(to be added later^)
    echo ;extension=php_sqlsrv.dll
    echo ;extension=php_pdo_sqlsrv.dll
    echo.
    echo ; Performance Settings
    echo memory_limit = 256M
    echo max_execution_time = 300
    echo upload_max_filesize = 64M
    echo post_max_size = 64M
    echo.
    echo ; Session Settings
    echo session.save_path = "C:/webserver/php/tmp"
    echo.
    echo ; Timezone
    echo date.timezone = "Asia/Bangkok"
) >> "C:\webserver\php\php.ini"

REM Create session directory
if not exist "C:\webserver\php\tmp\" mkdir "C:\webserver\php\tmp"

echo ✅ php.ini configured with common extensions
echo.

echo Step 4: Download Visual C++ Redistributable 2019 x64
echo =====================================================
echo.
echo Downloading Visual C++ Redistributable...

powershell -Command "& {
    try {
        Write-Host 'Downloading VC++ Redistributable 2019 x64...'
        $url = 'https://aka.ms/vs/17/release/vc_redist.x64.exe'
        $output = 'C:\webserver\temp\vc_redist.x64.exe'
        Invoke-WebRequest -Uri $url -OutFile $output -UseBasicParsing
        Write-Host 'Download completed'
        
        Write-Host 'Installing VC++ Redistributable (this may take a moment)...'
        Start-Process -FilePath $output -ArgumentList '/quiet', '/norestart' -Wait
        Write-Host 'VC++ Redistributable installed'
        
    } catch {
        Write-Host 'Error downloading/installing VC++ Redistributable: ' $_.Exception.Message
        Write-Host 'Please install manually from: https://aka.ms/vs/17/release/vc_redist.x64.exe'
    }
}"

echo.

echo Step 5: Download Microsoft ODBC Driver 18
echo ==========================================
echo.
echo Downloading ODBC Driver 18...

powershell -Command "& {
    try {
        Write-Host 'Downloading Microsoft ODBC Driver 18...'
        $url = 'https://go.microsoft.com/fwlink/?linkid=2187214'
        $output = 'C:\webserver\temp\msodbcsql.msi'
        Invoke-WebRequest -Uri $url -OutFile $output -UseBasicParsing
        Write-Host 'Download completed'
        
        Write-Host 'Installing ODBC Driver 18 (this may take a moment)...'
        Start-Process -FilePath 'msiexec.exe' -ArgumentList '/i', $output, '/quiet', '/norestart', 'IACCEPTMSODBCSQLLICENSETERMS=YES' -Wait
        Write-Host 'ODBC Driver 18 installed'
        
    } catch {
        Write-Host 'Error downloading/installing ODBC Driver: ' $_.Exception.Message
        Write-Host 'Please install manually from: https://go.microsoft.com/fwlink/?linkid=2187214'
    }
}"

echo.

echo Step 6: Download PHP SQLSRV Extensions
echo ======================================
echo.
echo Downloading SQLSRV extensions for PHP 8.3...

powershell -Command "& {
    try {
        Write-Host 'Downloading PHP SQLSRV Extensions...'
        $url = 'https://github.com/Microsoft/msphpsql/releases/download/v5.12.0/Windows-8.1-5.12.0-8.3-ts-x64.zip'
        $output = 'C:\webserver\temp\sqlsrv-extensions.zip'
        Invoke-WebRequest -Uri $url -OutFile $output -UseBasicParsing
        Write-Host 'Download completed'
        
        Write-Host 'Extracting SQLSRV extensions...'
        Expand-Archive -Path $output -DestinationPath 'C:\webserver\temp\sqlsrv' -Force
        
        Write-Host 'Copying extensions to PHP directory...'
        Copy-Item 'C:\webserver\temp\sqlsrv\php_sqlsrv.dll' 'C:\webserver\php\ext\' -Force
        Copy-Item 'C:\webserver\temp\sqlsrv\php_pdo_sqlsrv.dll' 'C:\webserver\php\ext\' -Force
        
        Write-Host 'SQLSRV extensions installed'
        
    } catch {
        Write-Host 'Error downloading SQLSRV extensions: ' $_.Exception.Message
        Write-Host 'Please download manually from: https://github.com/Microsoft/msphpsql/releases/latest'
    }
}"

echo.

echo Step 7: Enable SQLSRV Extensions in php.ini
echo =============================================
if exist "C:\webserver\php\ext\php_sqlsrv.dll" (
    echo Enabling SQLSRV extensions...
    
    REM Remove commented lines and add active extensions
    powershell -Command "& {
        $content = Get-Content 'C:\webserver\php\php.ini' | Where-Object { $_ -notmatch ';extension=php_sqlsrv.dll' -and $_ -notmatch ';extension=php_pdo_sqlsrv.dll' }
        $content += 'extension=php_sqlsrv.dll'
        $content += 'extension=php_pdo_sqlsrv.dll'
        $content | Set-Content 'C:\webserver\php\php.ini'
    }"
    
    echo ✅ SQLSRV extensions enabled
) else (
    echo ⚠️ SQLSRV extensions not found, leaving commented in php.ini
)

echo.

echo Step 8: Test PHP Installation
echo =============================
echo.
echo Testing PHP installation...
if exist "C:\webserver\php\php.exe" (
    echo ✅ PHP executable found
    
    echo.
    echo PHP Version:
    C:\webserver\php\php.exe -v
    
    echo.
    echo Testing PHP configuration...
    C:\webserver\php\php.exe -t
    if %ERRORLEVEL% equ 0 (
        echo ✅ PHP configuration is valid
    ) else (
        echo ❌ PHP configuration has errors
    )
    
    echo.
    echo Loaded Extensions:
    C:\webserver\php\php.exe -m | findstr -i "sqlsrv\|mysqli\|curl\|intl\|mbstring"
    
) else (
    echo ❌ PHP executable not found
)

echo.

echo Step 9: Add PHP to System PATH
echo ===============================
echo.
echo Adding C:\webserver\php to system PATH...

powershell -Command "& {
    try {
        $currentPath = [Environment]::GetEnvironmentVariable('PATH', 'Machine')
        if ($currentPath -notlike '*C:\webserver\php*') {
            $newPath = $currentPath + ';C:\webserver\php'
            [Environment]::SetEnvironmentVariable('PATH', $newPath, 'Machine')
            Write-Host 'PHP added to system PATH'
        } else {
            Write-Host 'PHP already in system PATH'
        }
    } catch {
        Write-Host 'Error updating PATH: ' $_.Exception.Message
        Write-Host 'Please add C:\webserver\php to PATH manually'
    }
}"

echo.

echo Step 10: Create PHP Info Page
echo =============================
(
    echo ^<?php
    echo phpinfo^(^);
    echo ?^>
) > "C:\webserver\htdocs\bme4\public\phpinfo.php"

echo ✅ Created phpinfo.php for testing
echo.

echo Step 11: Restart Apache
echo =======================
cd /d "C:\webserver\Apache2\bin"
echo Stopping Apache...
httpd.exe -k stop 2>nul
timeout /t 3 /nobreak >nul

echo Starting Apache...
httpd.exe -k start
if %ERRORLEVEL% equ 0 (
    echo ✅ Apache started successfully
) else (
    echo ❌ Apache failed to start - check configuration
)

echo.

echo Step 12: Clean up temporary files
echo =================================
if exist "C:\webserver\temp\" (
    rmdir /s /q "C:\webserver\temp\" 2>nul
    echo ✅ Temporary files cleaned up
)

echo.
echo =====================================
echo PHP Setup Complete!
echo =====================================
echo.
echo Installation Summary:
echo ✅ PHP 8.3.24 Thread Safe x64
echo ✅ Visual C++ Redistributable 2019
echo ✅ Microsoft ODBC Driver 18
echo ✅ PHP SQLSRV Extensions
echo ✅ Configured php.ini
echo ✅ Added to system PATH
echo.
echo Test URLs:
echo - PHP Info: http://192.168.0.21/phpinfo.php
echo - BME4 App: http://192.168.0.21/
echo.
echo Log Files:
echo - Apache Error: C:\webserver\Apache2\logs\error.log
echo - Apache Access: C:\webserver\Apache2\logs\access.log
echo.
echo If BME4 still shows database errors, check:
echo 1. SQL Server is running and accessible
echo 2. Database credentials in .env file
echo 3. Network connectivity to TH-BP-DB\MSSQL2017
echo.
echo Press any key to exit...
pause >nul