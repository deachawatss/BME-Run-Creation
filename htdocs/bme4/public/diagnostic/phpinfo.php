<?php
// Security check
if (!in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1', '192.168.0.21'])) {
    die('Access denied: PHP info only available from local network');
}

phpinfo();
?>