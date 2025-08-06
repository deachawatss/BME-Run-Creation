<?php
// SQLSRV Extension Diagnostic Tool
?>
<!DOCTYPE html>
<html>
<head>
    <title>SQLSRV Extension Diagnostic</title>
    <style>
        body { font-family: monospace; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .section { margin: 20px 0; padding: 15px; border-left: 4px solid #007bff; background: #f8f9fa; }
        .success { border-left-color: #28a745; background: #d4edda; }
        .error { border-left-color: #dc3545; background: #f8d7da; }
        .warning { border-left-color: #ffc107; background: #fff3cd; }
        pre { background: #e9ecef; padding: 10px; border-radius: 4px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background: #e9ecef; }
        .btn { display: inline-block; padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>SQLSRV Extension Diagnostic</h1>
        <p><strong>Server:</strong> <?php echo $_SERVER['HTTP_HOST']; ?> | <strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <!-- PHP System Information -->
        <div class="section">
            <h2>PHP System Information</h2>
            <table>
                <tr><th>Property</th><th>Value</th><th>Expected</th><th>Status</th></tr>
                <tr>
                    <td>PHP Version</td>
                    <td><?php echo PHP_VERSION; ?></td>
                    <td>8.3.24</td>
                    <td><?php echo version_compare(PHP_VERSION, '8.3.0', '>=') ? 'OK' : 'ERROR'; ?></td>
                </tr>
                <tr>
                    <td>Architecture</td>
                    <td><?php echo php_uname('m'); ?> (<?php echo PHP_INT_SIZE * 8; ?>-bit)</td>
                    <td>x64 (64-bit)</td>
                    <td><?php echo PHP_INT_SIZE === 8 ? 'OK' : 'ERROR'; ?></td>
                </tr>
                <tr>
                    <td>Thread Safety</td>
                    <td><?php echo ZEND_THREAD_SAFE ? 'Thread Safe' : 'Non-Thread Safe'; ?></td>
                    <td>Thread Safe</td>
                    <td><?php echo ZEND_THREAD_SAFE ? 'OK' : 'ERROR'; ?></td>
                </tr>
                <tr>
                    <td>PHP SAPI</td>
                    <td><?php echo php_sapi_name(); ?></td>
                    <td>apache2handler</td>
                    <td><?php echo php_sapi_name() === 'apache2handler' ? 'OK' : 'WARNING'; ?></td>
                </tr>
                <tr>
                    <td>Extension Directory</td>
                    <td><?php echo ini_get('extension_dir'); ?></td>
                    <td>ext</td>
                    <td>INFO</td>
                </tr>
            </table>
        </div>

        <!-- Extension Status -->
        <div class="section">
            <h2>Extension Loading Status</h2>
            <table>
                <tr><th>Extension</th><th>Status</th><th>Function Test</th><th>Details</th></tr>
                <tr>
                    <td>SQLSRV</td>
                    <td><?php echo extension_loaded('sqlsrv') ? 'LOADED' : 'NOT LOADED'; ?></td>
                    <td><?php echo function_exists('sqlsrv_configure') ? 'AVAILABLE' : 'MISSING'; ?></td>
                    <td><?php echo extension_loaded('sqlsrv') ? 'Core functions available' : 'Extension not found'; ?></td>
                </tr>
                <tr>
                    <td>PDO_SQLSRV</td>
                    <td><?php echo extension_loaded('pdo_sqlsrv') ? 'LOADED' : 'NOT LOADED'; ?></td>
                    <td><?php echo class_exists('PDO') && in_array('sqlsrv', PDO::getAvailableDrivers()) ? 'AVAILABLE' : 'MISSING'; ?></td>
                    <td><?php echo in_array('sqlsrv', PDO::getAvailableDrivers() ?? []) ? 'PDO driver registered' : 'PDO driver not found'; ?></td>
                </tr>
            </table>
        </div>

        <!-- All Loaded Extensions -->
        <div class="section">
            <h2>Currently Loaded Extensions</h2>
            <div style="column-count: 3; column-gap: 20px;">
                <?php
                $extensions = get_loaded_extensions();
                sort($extensions);
                foreach ($extensions as $ext) {
                    $color = in_array(strtolower($ext), ['sqlsrv', 'pdo_sqlsrv']) ? 'color: green; font-weight: bold;' : '';
                    echo "<div style='break-inside: avoid; margin: 2px 0; $color'>$ext</div>";
                }
                ?>
            </div>
            <p><strong>Total:</strong> <?php echo count($extensions); ?> extensions loaded</p>
        </div>

        <!-- PDO Drivers -->
        <div class="section">
            <h2>Available PDO Drivers</h2>
            <?php if (class_exists('PDO')): ?>
                <pre><?php print_r(PDO::getAvailableDrivers()); ?></pre>
                <p><strong>SQLSRV Driver:</strong> <?php echo in_array('sqlsrv', PDO::getAvailableDrivers()) ? 'AVAILABLE' : 'NOT FOUND'; ?></p>
            <?php else: ?>
                <p class="error">ERROR: PDO class not available</p>
            <?php endif; ?>
        </div>

        <!-- Configuration Check -->
        <div class="section">
            <h2>PHP Configuration</h2>
            <table>
                <tr><th>Setting</th><th>Value</th></tr>
                <tr><td>extension_dir</td><td><?php echo ini_get('extension_dir'); ?></td></tr>
                <tr><td>log_errors</td><td><?php echo ini_get('log_errors') ? 'On' : 'Off'; ?></td></tr>
                <tr><td>error_log</td><td><?php echo ini_get('error_log') ?: 'Not set'; ?></td></tr>
                <tr><td>display_errors</td><td><?php echo ini_get('display_errors') ? 'On' : 'Off'; ?></td></tr>
                <tr><td>display_startup_errors</td><td><?php echo ini_get('display_startup_errors') ? 'On' : 'Off'; ?></td></tr>
            </table>
        </div>

        <!-- Diagnostic Summary -->
        <div class="section <?php echo (extension_loaded('sqlsrv') && extension_loaded('pdo_sqlsrv')) ? 'success' : 'error'; ?>">
            <h2>Diagnostic Summary</h2>
            <?php if (extension_loaded('sqlsrv') && extension_loaded('pdo_sqlsrv')): ?>
                <h3>SUCCESS: SQLSRV Extensions Working!</h3>
                <p>Both SQLSRV and PDO_SQLSRV extensions are loaded and functional.</p>
                <a href="../" class="btn">Test BME4 Application</a>
            <?php else: ?>
                <h3>ISSUE: SQLSRV Extensions Not Loading</h3>
                <p><strong>Possible Causes:</strong></p>
                <ul>
                    <?php if (!ZEND_THREAD_SAFE): ?>
                        <li>ERROR: PHP is Non-Thread Safe but extensions require Thread Safe</li>
                    <?php endif; ?>
                    <?php if (PHP_INT_SIZE !== 8): ?>
                        <li>ERROR: PHP is 32-bit but extensions are 64-bit</li>
                    <?php endif; ?>
                    <li>WARNING: Extension files may be incompatible with PHP version</li>
                    <li>WARNING: Missing Visual C++ Redistributable or ODBC Driver</li>
                    <li>WARNING: DLL dependency issues</li>
                </ul>
                
                <p><strong>Recommended Fixes:</strong></p>
                <ol>
                    <li>Restart Apache: <code>C:\webserver\Apache2\bin\httpd.exe -k restart</code></li>
                    <li>Verify Visual C++ Redistributable 2019 x64 is installed</li>
                    <li>Verify Microsoft ODBC Driver 18 is installed</li>
                    <li>Check Windows Event Viewer for DLL loading errors</li>
                </ol>
                
                <p><strong>MySQL Fallback (if SQLSRV won't work):</strong></p>
                <pre>copy .env.mysql-ready .env</pre>
                
                <a href="check-extensions.php" class="btn">Refresh Check</a>
            <?php endif; ?>
        </div>

        <!-- Quick Links -->
        <div class="section">
            <h2>Quick Links</h2>
            <a href="../" class="btn">BME4 Home</a>
            <a href="../../../" class="btn">Server Root</a>
            <a href="phpinfo.php" class="btn">PHP Info</a>
        </div>
    </div>
</body>
</html>