<?php
// LDAP Extension Diagnostic Tool
?>
<!DOCTYPE html>
<html>
<head>
    <title>LDAP Extension Diagnostic</title>
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
        <h1>LDAP Extension Diagnostic</h1>
        <p><strong>Server:</strong> <?php echo $_SERVER['HTTP_HOST']; ?> | <strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <!-- LDAP Extension Status -->
        <div class="section <?php echo extension_loaded('ldap') ? 'success' : 'error'; ?>">
            <h2>LDAP Extension Status</h2>
            <?php if (extension_loaded('ldap')): ?>
                <h3>SUCCESS: LDAP Extension Loaded</h3>
                <p>PHP LDAP extension is available and ready for Active Directory authentication.</p>
                
                <h3>Available LDAP Functions</h3>
                <div style="column-count: 3; column-gap: 20px;">
                    <?php
                    $ldapFunctions = get_extension_funcs('ldap');
                    if ($ldapFunctions) {
                        foreach ($ldapFunctions as $func) {
                            echo "<div style='break-inside: avoid; margin: 2px 0;'>$func</div>";
                        }
                    }
                    ?>
                </div>
                <p><strong>Total LDAP Functions:</strong> <?php echo $ldapFunctions ? count($ldapFunctions) : 0; ?></p>

            <?php else: ?>
                <h3>ERROR: LDAP Extension Not Loaded</h3>
                <p>PHP LDAP extension is not available. This is required for Active Directory authentication.</p>
                
                <h3>How to Fix:</h3>
                <ol>
                    <li>Add <code>extension=ldap</code> to your php.ini file</li>
                    <li>Ensure the LDAP extension DLL is present in your PHP ext directory</li>
                    <li>Restart Apache after making changes</li>
                </ol>
                
                <h3>Check These Locations:</h3>
                <ul>
                    <li><strong>PHP ini file:</strong> <?php echo php_ini_loaded_file(); ?></li>
                    <li><strong>Extension directory:</strong> <?php echo ini_get('extension_dir'); ?></li>
                    <li><strong>Look for:</strong> php_ldap.dll</li>
                </ul>
            <?php endif; ?>
        </div>

        <!-- LDAP Configuration Test -->
        <?php if (extension_loaded('ldap')): ?>
        <div class="section">
            <h2>LDAP Configuration Test</h2>
            <?php
            try {
                // Load LDAP configuration
                if (class_exists('\App\Services\LdapService')) {
                    $ldapService = new \App\Services\LdapService();
                    $testResult = $ldapService->testConnection();
                    
                    if ($testResult['success']) {
                        echo '<div class="success">';
                        echo '<h3>SUCCESS: Connection Test Passed</h3>';
                        echo '<p>' . htmlspecialchars($testResult['message']) . '</p>';
                        echo '</div>';
                    } else {
                        echo '<div class="error">';
                        echo '<h3>ERROR: Connection Test Failed</h3>';
                        echo '<p>' . htmlspecialchars($testResult['message']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="warning">';
                    echo '<h3>WARNING: LDAP Service Not Available</h3>';
                    echo '<p>LdapService class not found. Check if the service is properly configured.</p>';
                    echo '</div>';
                }
            } catch (Exception $e) {
                echo '<div class="error">';
                echo '<h3>ERROR: Configuration Test Failed</h3>';
                echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '</div>';
            }
            ?>
        </div>
        <?php endif; ?>

        <!-- Current Configuration -->
        <div class="section">
            <h2>Current LDAP Configuration</h2>
            <?php if (extension_loaded('ldap')): ?>
                <?php
                try {
                    // Try to load LDAP config
                    if (file_exists(__DIR__ . '/../../../app/Config/Ldap.php')) {
                        require_once __DIR__ . '/../../../app/Config/Ldap.php';
                        $ldapConfig = new \Config\Ldap();
                        ?>
                        <table>
                            <tr><th>Setting</th><th>Value</th></tr>
                            <tr><td>Enabled</td><td><?php echo $ldapConfig->enabled ? 'Yes' : 'No'; ?></td></tr>
                            <tr><td>Host</td><td><?php echo htmlspecialchars($ldapConfig->host); ?></td></tr>
                            <tr><td>Port</td><td><?php echo $ldapConfig->port; ?></td></tr>
                            <tr><td>Use SSL</td><td><?php echo $ldapConfig->useSsl ? 'Yes' : 'No'; ?></td></tr>
                            <tr><td>Use TLS</td><td><?php echo $ldapConfig->useTls ? 'Yes' : 'No'; ?></td></tr>
                            <tr><td>Domain</td><td><?php echo htmlspecialchars($ldapConfig->domain); ?></td></tr>
                            <tr><td>Base DN</td><td><?php echo htmlspecialchars($ldapConfig->baseDn); ?></td></tr>
                            <tr><td>User DN</td><td><?php echo htmlspecialchars($ldapConfig->userDn); ?></td></tr>
                            <tr><td>Bind User</td><td><?php echo htmlspecialchars($ldapConfig->bindUser); ?></td></tr>
                            <tr><td>Auth Fallback</td><td><?php echo $ldapConfig->authFallback ? 'Yes' : 'No'; ?></td></tr>
                            <tr><td>Create Users</td><td><?php echo $ldapConfig->createUsers ? 'Yes' : 'No'; ?></td></tr>
                            <tr><td>Update Users</td><td><?php echo $ldapConfig->updateUsers ? 'Yes' : 'No'; ?></td></tr>
                        </table>
                        <?php
                    } else {
                        echo '<p class="error">LDAP configuration file not found.</p>';
                    }
                } catch (Exception $e) {
                    echo '<p class="error">Error loading LDAP configuration: ' . htmlspecialchars($e->getMessage()) . '</p>';
                }
                ?>
            <?php else: ?>
                <p>LDAP extension must be loaded to display configuration.</p>
            <?php endif; ?>
        </div>

        <!-- Quick Links -->
        <div class="section">
            <h2>Quick Links</h2>
            <a href="../" class="btn">BME4 Home</a>
            <a href="check-extensions.php" class="btn">Extension Check</a>
            <a href="phpinfo.php" class="btn">PHP Info</a>
            <?php if (extension_loaded('ldap')): ?>
                <a href="ldap-test.php" class="btn">Test LDAP Authentication</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>