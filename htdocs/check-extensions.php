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
        <h1>🔍 SQLSRV Extension Diagnostic</h1>
        <p><strong>Server:</strong> <?php echo $_SERVER['HTTP_HOST']; ?> | <strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <!-- PHP System Information -->
        <div class="section">
            <h2>📊 PHP System Information</h2>
            <table>
                <tr><th>Property</th><th>Value</th><th>Expected</th><th>Status</th></tr>
                <tr>
                    <td>PHP Version</td>
                    <td><?php echo PHP_VERSION; ?></td>
                    <td>8.3.24</td>
                    <td><?php echo version_compare(PHP_VERSION, '8.3.0', '>=') ? '✅' : '❌'; ?></td>
                </tr>
                <tr>
                    <td>Architecture</td>
                    <td><?php echo php_uname('m'); ?> (<?php echo PHP_INT_SIZE * 8; ?>-bit)</td>
                    <td>x64 (64-bit)</td>
                    <td><?php echo PHP_INT_SIZE === 8 ? '✅' : '❌'; ?></td>
                </tr>
                <tr>
                    <td>Thread Safety</td>
                    <td><?php echo ZEND_THREAD_SAFE ? 'Thread Safe' : 'Non-Thread Safe'; ?></td>
                    <td>Thread Safe</td>
                    <td><?php echo ZEND_THREAD_SAFE ? '✅' : '❌'; ?></td>
                </tr>
                <tr>
                    <td>PHP SAPI</td>
                    <td><?php echo php_sapi_name(); ?></td>
                    <td>apache2handler</td>
                    <td><?php echo php_sapi_name() === 'apache2handler' ? '✅' : '⚠️'; ?></td>
                </tr>
                <tr>
                    <td>Extension Directory</td>
                    <td><?php echo ini_get('extension_dir'); ?></td>
                    <td>ext</td>
                    <td>ℹ️</td>
                </tr>
            </table>
        </div>

        <!-- Extension Status -->
        <div class="section">
            <h2>🔌 Extension Loading Status</h2>
            <table>
                <tr><th>Extension</th><th>Status</th><th>Function Test</th><th>Details</th></tr>
                <tr>
                    <td>SQLSRV</td>
                    <td><?php echo extension_loaded('sqlsrv') ? '✅ Loaded' : '❌ Not Loaded'; ?></td>
                    <td><?php echo function_exists('sqlsrv_configure') ? '✅ Available' : '❌ Missing'; ?></td>
                    <td><?php echo extension_loaded('sqlsrv') ? 'Core functions available' : 'Extension not found'; ?></td>
                </tr>
                <tr>
                    <td>PDO_SQLSRV</td>
                    <td><?php echo extension_loaded('pdo_sqlsrv') ? '✅ Loaded' : '❌ Not Loaded'; ?></td>
                    <td><?php echo class_exists('PDO') && in_array('sqlsrv', PDO::getAvailableDrivers()) ? '✅ Available' : '❌ Missing'; ?></td>
                    <td><?php echo in_array('sqlsrv', PDO::getAvailableDrivers() ?? []) ? 'PDO driver registered' : 'PDO driver not found'; ?></td>
                </tr>
            </table>
        </div>

        <!-- All Loaded Extensions -->
        <div class="section">
            <h2>📦 Currently Loaded Extensions</h2>
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
            <h2>🗄️ Available PDO Drivers</h2>
            <?php if (class_exists('PDO')): ?>
                <pre><?php print_r(PDO::getAvailableDrivers()); ?></pre>
                <p><strong>SQLSRV Driver:</strong> <?php echo in_array('sqlsrv', PDO::getAvailableDrivers()) ? '✅ Available' : '❌ Not Found'; ?></p>
            <?php else: ?>
                <p class="error">❌ PDO class not available</p>
            <?php endif; ?>
        </div>

        <!-- File System Check -->
        <div class="section">
            <h2>📁 Extension Files Check</h2>
            <?php
            $extDir = ini_get('extension_dir');
            $sqlsrvFile = $extDir . DIRECTORY_SEPARATOR . 'php_sqlsrv.dll';
            $pdoSqlsrvFile = $extDir . DIRECTORY_SEPARATOR . 'php_pdo_sqlsrv.dll';
            ?>
            <table>
                <tr><th>File</th><th>Path</th><th>Exists</th><th>Size</th><th>Modified</th></tr>
                <tr>
                    <td>php_sqlsrv.dll</td>
                    <td><?php echo $sqlsrvFile; ?></td>
                    <td><?php echo file_exists($sqlsrvFile) ? '✅ Yes' : '❌ No'; ?></td>
                    <td><?php echo file_exists($sqlsrvFile) ? number_format(filesize($sqlsrvFile)) . ' bytes' : 'N/A'; ?></td>
                    <td><?php echo file_exists($sqlsrvFile) ? date('Y-m-d H:i:s', filemtime($sqlsrvFile)) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td>php_pdo_sqlsrv.dll</td>
                    <td><?php echo $pdoSqlsrvFile; ?></td>
                    <td><?php echo file_exists($pdoSqlsrvFile) ? '✅ Yes' : '❌ No'; ?></td>
                    <td><?php echo file_exists($pdoSqlsrvFile) ? number_format(filesize($pdoSqlsrvFile)) . ' bytes' : 'N/A'; ?></td>
                    <td><?php echo file_exists($pdoSqlsrvFile) ? date('Y-m-d H:i:s', filemtime($pdoSqlsrvFile)) : 'N/A'; ?></td>
                </tr>
            </table>
        </div>

        <!-- Configuration Check -->
        <div class="section">
            <h2>⚙️ PHP Configuration</h2>
            <table>
                <tr><th>Setting</th><th>Value</th></tr>
                <tr><td>extension_dir</td><td><?php echo ini_get('extension_dir'); ?></td></tr>
                <tr><td>log_errors</td><td><?php echo ini_get('log_errors') ? 'On' : 'Off'; ?></td></tr>
                <tr><td>error_log</td><td><?php echo ini_get('error_log') ?: 'Not set'; ?></td></tr>
                <tr><td>display_errors</td><td><?php echo ini_get('display_errors') ? 'On' : 'Off'; ?></td></tr>
                <tr><td>display_startup_errors</td><td><?php echo ini_get('display_startup_errors') ? 'On' : 'Off'; ?></td></tr>
            </table>
        </div>

        <!-- System Dependencies -->
        <div class="section">
            <h2>🔧 System Dependencies</h2>
            <table>
                <tr><th>Dependency</th><th>Status</th><th>Notes</th></tr>
                <tr>
                    <td>Visual C++ Redistributable 2019</td>
                    <td>⚠️ Cannot detect from PHP</td>
                    <td>Required for SQLSRV extensions</td>
                </tr>
                <tr>
                    <td>Microsoft ODBC Driver 18</td>
                    <td>⚠️ Cannot detect from PHP</td>
                    <td>Required for SQL Server connectivity</td>
                </tr>
            </table>
        </div>

        <!-- Diagnostic Summary -->
        <div class="section <?php echo (extension_loaded('sqlsrv') && extension_loaded('pdo_sqlsrv')) ? 'success' : 'error'; ?>">
            <h2>📋 Diagnostic Summary</h2>
            <?php if (extension_loaded('sqlsrv') && extension_loaded('pdo_sqlsrv')): ?>
                <h3>✅ SUCCESS: SQLSRV Extensions Working!</h3>
                <p>Both SQLSRV and PDO_SQLSRV extensions are loaded and functional.</p>
                <a href="/bme4/public/" class="btn">🎯 Test BME4 Application</a>
            <?php else: ?>
                <h3>❌ ISSUE: SQLSRV Extensions Not Loading</h3>
                <p><strong>Possible Causes:</strong></p>
                <ul>
                    <?php if (!ZEND_THREAD_SAFE): ?>
                        <li>❌ PHP is Non-Thread Safe but extensions require Thread Safe</li>
                    <?php endif; ?>
                    <?php if (PHP_INT_SIZE !== 8): ?>
                        <li>❌ PHP is 32-bit but extensions are 64-bit</li>
                    <?php endif; ?>
                    <li>⚠️ Extension files may be incompatible with PHP version</li>
                    <li>⚠️ Missing Visual C++ Redistributable or ODBC Driver</li>
                    <li>⚠️ DLL dependency issues</li>
                </ul>
                
                <p><strong>Recommended Fixes:</strong></p>
                <ol>
                    <li>Verify Visual C++ Redistributable 2019 x64 is installed</li>
                    <li>Verify Microsoft ODBC Driver 18 is installed</li>
                    <li>Check Windows Event Viewer for DLL loading errors</li>
                    <li>Try restarting Apache after installing dependencies</li>
                </ol>
                
                <a href="/htdocs/check-extensions.php" class="btn">🔄 Refresh Check</a>
                <a href="/phpinfo.php" class="btn">📊 Full PHP Info</a>
            <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="section">
            <h2>🚀 Quick Actions</h2>
            <a href="/" class="btn">🏠 Dashboard</a>
            <a href="/phpinfo.php" class="btn">📊 PHP Info</a>
            <a href="/bme4/public/" class="btn">🎯 BME4 App</a>
        </div>
    </div>
</body>
</html>