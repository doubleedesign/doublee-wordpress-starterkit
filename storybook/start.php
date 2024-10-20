<?php
// Get the current directory and replace backslashes with forward slashes
$currentDir = str_replace('\\', '/', __DIR__);

// Create/update php.ini file with the correct paths
$phpIniContent = "
auto_prepend_file = $currentDir/wrapper-open.php
auto_append_file = $currentDir/wrapper-close.php
";

// Path for the php.ini file
$phpIniPath = $currentDir . '/php.ini';

// Write the content to the file
file_put_contents($phpIniPath, $phpIniContent);

// Port to use
$port = 6001;

// Kill any hanging previous PHP server processes (otherwise php.ini caching issues can happen)
exec("netstat -ano | findstr :$port", $output);
if (!empty($output)) {
    foreach ($output as $line) {
        if (preg_match('/\s+(\d+)\s*$/', $line, $matches)) {
            $pid = $matches[1];
            exec("taskkill /PID $pid /F");
        }
    }
}

// Start the server
$cmd = "php -S localhost:$port -c $phpIniPath -t ./components";
exec($cmd);
