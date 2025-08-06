<?php
// PHP Information Page - Use to diagnose Apache PHP configuration
echo "<h1>PHP Configuration for Apache</h1>";
echo "<h2>PHP Version: " . PHP_VERSION . "</h2>";

// Check loaded extensions
echo "<h3>Loaded Extensions:</h3>";
$extensions = get_loaded_extensions();
sort($extensions);
foreach($extensions as $ext) {
    echo $ext . "<br>";
}

// Check specifically for intl and mbstring
echo "<h3>Required Extensions Status:</h3>";
echo "intl: " . (extension_loaded('intl') ? '<strong style="color:green">LOADED</strong>' : '<strong style="color:red">NOT LOADED</strong>') . "<br>";
echo "mbstring: " . (extension_loaded('mbstring') ? '<strong style="color:green">LOADED</strong>' : '<strong style="color:red">NOT LOADED</strong>') . "<br>";

// Show php.ini file location
echo "<h3>Configuration File Info:</h3>";
echo "Loaded php.ini: " . php_ini_loaded_file() . "<br>";
echo "Additional ini files: " . php_ini_scanned_files() . "<br>";

// Show extension directory
echo "<h3>Extension Directory:</h3>";
echo "Extension dir: " . ini_get('extension_dir') . "<br>";

// Full phpinfo
echo "<h3>Full PHP Info:</h3>";
phpinfo();
?>