<?php

/*
----------------------------------
 ------  Created: 101124   ------
 ------  Austin Best	   ------
----------------------------------
*/

error_reporting(E_ERROR | E_PARSE);

if (!defined('ABSOLUTE_PATH')) {
    if (file_exists('loader.php')) {
        define('ABSOLUTE_PATH', './');
    }
    if (file_exists('../loader.php')) {
        define('ABSOLUTE_PATH', '../');
    }
    if (file_exists('../../loader.php')) {
        define('ABSOLUTE_PATH', '../../');
    }
}

//-- SETUP SOME SHARED VARIABLES
$page       = $_GET['page'] ?: $_POST['page'];
$app        = $_GET['app'] ?: $_POST['app'];
$appLabel   = ucfirst($app);

//-- DIRECTORIES TO LOAD FILES FROM, ORDER IS IMPORTANT
$autoloads          = ['includes', 'functions', 'functions/helpers','classes'];
$ignoreAutoloads    = ['header.php', 'footer.php'];

foreach ($autoloads as $autoload) {
    $dir = ABSOLUTE_PATH . $autoload;

    if (is_dir($dir)) {
        $handle = opendir($dir);
        while ($file = readdir($handle)) {
            if ($file[0] != '.' && !is_dir($dir . '/' . $file) && !in_array($file, $ignoreAutoloads)) {
                require $dir . '/' . $file;
            }
        }
        closedir($handle);
    }
}

//-- CREATE NEEDED FOLDERS
$createFolders = [APP_LOG_PATH, APP_BACKUP_PATH, APP_USER_TEMPLATES_PATH];
foreach ($createFolders as $createFolder) {
    if (!is_dir($createFolder)) {
        shell_exec('mkdir -p ' . $createFolder);
    }
}
foreach ($starrApps as $starrApp) {
    if (!is_dir(APP_USER_TEMPLATES_PATH . $starrApp)) {
        shell_exec('mkdir -p ' . APP_USER_TEMPLATES_PATH . $starrApp);
    }
    if (!is_dir(ABSOLUTE_PATH . 'templates/' . $starrApp)) {
        shell_exec('mkdir -p ' . ABSOLUTE_PATH . 'templates/' . $starrApp);
    }
}

//-- CREATE APIKEY IF MISSING
if (!file_exists(APP_APIKEY_FILE)) {
    $key = generateApikey();
    file_put_contents(APP_APIKEY_FILE, $key);
}
define('APP_APIKEY', file_get_contents(APP_APIKEY_FILE));

//-- LOAD THE FILES
$settingsFile   = getFile(APP_SETTINGS_FILE);
$usageFile      = getFile(APP_USAGE_FILE);
