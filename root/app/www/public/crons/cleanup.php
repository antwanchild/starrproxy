<?php

/*
----------------------------------
 ------  Created: 101124   ------
 ------  Austin Best	   ------
----------------------------------
*/

if (!defined('ABSOLUTE_PATH')) {
    if (is_dir('/app')) {
        define('ABSOLUTE_PATH', '/app/www/public/');
    } else {
        if (file_exists('loader.php')) {
            define('ABSOLUTE_PATH', './');
        } elseif (file_exists('../loader.php')) {
            define('ABSOLUTE_PATH', '../');
        } elseif (file_exists('../../loader.php')) {
            define('ABSOLUTE_PATH', '../../');
        }
    }
}

require ABSOLUTE_PATH . 'loader.php';

//-- OLD LOGS
if (is_dir(LOGS_PATH)) {
    $dir = opendir(LOGS_PATH);
    while ($file = readdir($dir)) {
        $logfile = LOGS_PATH . $file;

        if (!str_contains($logfile, '.log')) {
            continue;
        }

        if (filemtime($logfile) <= (time() - (86400 * LOG_AGE))) {
            echo date('c') . ' removing old logfile \'' . $logfile . '\''."\n";
            $shell->exec('rm ' . $logfile);
        }
    }
    closedir($dir);
}

//-- OLD BACKUPS
if (is_dir(BACKUP_PATH)) {
    $dir = opendir(BACKUP_PATH);
    while ($folder = readdir($dir)) {
        $backupFolder = BACKUP_PATH . $folder;

        //-- NOTIFIARR CORRUPTION CHECKS
        if (str_contains($backupFolder, '.zip')) {
            if (filemtime($backupFolder) <= (time() - (86400 * STARR_BACKUP_AGE))) {
                echo date('c') . ' removing old starr backup \'' . $backupFolder . '\''."\n";
                $shell->exec('rm ' . $backupFolder);
            }
        }

        if (!is_dir($backupFolder) || $folder[0] == '.') {
            continue;
        }

        if (filemtime($backupFolder) <= (time() - (86400 * BACKUP_AGE))) {
            echo date('c') . ' removing old backup \'' . $backupFolder . '\''."\n";
            $shell->exec('rm -r ' . $backupFolder);
        }
    }
    closedir($dir);
}
