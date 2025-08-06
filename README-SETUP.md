# BME4 Webserver - Ready to Use Setup

## 🚀 Quick Start

After `git pull origin main`, BME4 is ready to use with **ZERO configuration**!

### Prerequisites (Install these on Windows Server first):
1. **Visual C++ Redistributable 2019 x64**: https://aka.ms/vs/17/release/vc_redist.x64.exe
2. **Microsoft ODBC Driver 18**: https://go.microsoft.com/fwlink/?linkid=2187214

### Test Your Setup:
1. **Start Apache**: `C:\webserver\Apache2\bin\httpd.exe -k start`
2. **Test PHP**: http://192.168.0.21/phpinfo.php
3. **Test BME4**: http://192.168.0.21/

## 📁 What's Ready:

### ✅ PHP 8.3.24 Complete Installation
- **Location**: `C:\webserver\php\`
- **PHP Executable**: `php.exe`
- **Apache Module**: `php8apache2_4.dll`
- **Configuration**: Optimized `php.ini` with production settings

### ✅ All Extensions Enabled:
- **Core**: curl, fileinfo, gd, intl, mbstring, openssl, zip
- **Database**: mysqli, pdo_mysql
- **SQL Server**: php_sqlsrv.dll, php_pdo_sqlsrv.dll ⭐
- **Performance**: opcache (JIT enabled)

### ✅ Apache Configuration:
- **PHP Path**: Updated to `C:\webserver\php`
- **BME4 Virtual Host**: Ready at `http://192.168.0.21/`
- **Document Root**: `C:\webserver\htdocs\bme4\public\`

### ✅ BME4 Application:
- **Environment**: Production ready (`.env` configured)
- **Database**: TFCMOBILE/TFCPILOT3 connections ready
- **Assets**: All CSS/JS files properly accessible

## 🎯 What Changed:

### No More Batch Scripts!
- ❌ **Removed**: All `.bat` files that were causing issues
- ✅ **Ready**: Everything pre-configured and working

### Production Optimizations:
- **Memory**: 256MB limit
- **Timezone**: Asia/Bangkok
- **Extensions**: All required extensions pre-enabled
- **Performance**: OPcache with JIT compilation

## 🔧 Troubleshooting:

### If BME4 Shows Database Errors:
1. **Install Prerequisites** (Visual C++ and ODBC Driver)
2. **Restart Apache**: `C:\webserver\Apache2\bin\httpd.exe -k restart`
3. **Check Logs**: `C:\webserver\Apache2\logs\error.log`

### If PHP Extensions Not Loading:
- Dependencies missing: Install Visual C++ 2019 and ODBC Driver 18
- Apache restart required after installing dependencies

### Database Connection Issues:
- Verify SQL Server is running: `TH-BP-DB\MSSQL2017:49381`
- Check network connectivity from Windows server
- Verify credentials in `.env` file

## 🎉 Success!

Your BME4 system is now ready to use with:
- ✅ Complete PHP 8.3.24 installation
- ✅ All SQL Server extensions working
- ✅ Optimized production configuration
- ✅ Zero manual setup required

Just install the 2 prerequisites and start Apache!