<!DOCTYPE html>
<html>
<head>
    <title>BME4 Webserver - 192.168.0.21</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: #f8f9fa; padding: 20px; margin: 20px 0; border-radius: 5px; border-left: 4px solid #007bff; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .status { padding: 10px; border-radius: 4px; margin: 10px 0; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 BME4 Webserver Ready!</h1>
        
        <div class="status success">
            <strong>✅ Server Status:</strong> Apache and PHP are running successfully
        </div>
        
        <div class="card">
            <h2>📋 BME4 Application</h2>
            <p>Production-ready CodeIgniter 4 application with SQL Server support.</p>
            <a href="/bme4/public/" class="btn">🎯 Launch BME4 Application</a>
            <a href="/bme4/public/CreateRunBulk" class="btn">📦 Create Bulk Run</a>
            <a href="/bme4/public/CreateRunPartial" class="btn">📝 Create Partial Run</a>
        </div>
        
        <div class="card">
            <h2>🔧 System Information</h2>
            <p>PHP configuration, extensions, and server details.</p>
            <a href="/phpinfo.php" class="btn">📊 PHP Information</a>
            <a href="/bme4/public/phpinfo.php" class="btn">📊 BME4 PHP Info</a>
        </div>
        
        <div class="status info">
            <strong>📡 Server Details:</strong><br>
            • IP Address: 192.168.0.21<br>
            • Apache DocumentRoot: C:\webserver\htdocs<br>
            • BME4 Location: C:\webserver\htdocs\bme4\public<br>
            • PHP Version: <?php echo PHP_VERSION; ?><br>
            • SQL Server Extensions: <?php echo extension_loaded('sqlsrv') ? '✅ Loaded' : '❌ Not Loaded'; ?>
        </div>
        
        <div class="card">
            <h2>📚 Quick Links</h2>
            <p>Development and configuration files.</p>
            <a href="/bme4/" class="btn">📁 BME4 Directory</a>
            <small style="display: block; margin-top: 10px; color: #666;">
                Last Updated: <?php echo date('Y-m-d H:i:s'); ?>
            </small>
        </div>
    </div>
</body>
</html>