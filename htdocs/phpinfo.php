<?php
// PHP Information Page for C:\webserver\htdocs\
// Shows all PHP configuration including loaded extensions
echo "<h1>PHP Configuration - BME4 Server</h1>";
echo "<p><strong>Test URLs:</strong></p>";
echo "<ul>";
echo "<li><a href='/'>Root Directory</a></li>";
echo "<li><a href='/bme4/public/'>BME4 Application</a></li>";
echo "<li><a href='/bme4/public/phpinfo.php'>BME4 PHPInfo</a></li>";
echo "</ul>";
echo "<hr>";

phpinfo();
?>