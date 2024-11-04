<?php

/*
----------------------------------
 ------  Created: 101124   ------
 ------  Austin Best	   ------
----------------------------------
*/

define('APP_NAME', 'Starr Proxy');
define('APP_API_ERROR', APP_NAME .': %s');
define('APP_URL', ($_SERVER['REQUEST_SCHEME'] ?: 'http') . '://'. $_SERVER['HTTP_HOST']);

define('LOG_AGE', 2); //-- DELETE AFTER THIS AMOUNT OF DAYS
define('BACKUP_AGE', 7); //-- DELETE AFTER THIS AMOUNT OF DAYS
define('STARR_BACKUP_AGE', 2); //-- DELETE AFTER THIS AMOUNT OF DAYS

//-- DATABASE
define('PROXY_DATABASE_NAME', 'starrproxy.db');
define('USAGE_DATABASE_NAME', 'usage.db');
define('SETTINGS_TABLE', 'settings');
define('STARRS_TABLE', 'starrs');
define('APPS_TABLE', 'apps');
define('NOTIFICATION_PLATFORM_TABLE', 'notification_platform');
define('NOTIFICATION_TRIGGER_TABLE', 'notification_trigger');
define('NOTIFICATION_LINK_TABLE', 'notification_link');
define('USAGE_TABLE', 'usage');

//-- FOLDERS
define('APP_DATA_PATH', '/config/');
define('LOGS_PATH', APP_DATA_PATH . 'logs/');
define('BACKUP_PATH', APP_DATA_PATH . 'backups/');
define('APP_USER_TEMPLATES_PATH', APP_DATA_PATH . 'templates/');
define('DATABASE_PATH', APP_DATA_PATH . 'database/');
define('MIGRATIONS_PATH', ABSOLUTE_PATH . 'migrations/');

//-- FILES
define('SETTINGS_FILE', APP_DATA_PATH . 'settings.json');
define('APP_APIKEY_FILE', APP_DATA_PATH . 'key');
define('APP_USAGE_FILE', APP_DATA_PATH . 'usage.json');
define('MIGRATION_FILE', APP_DATA_PATH . 'migration-in-progress.txt');
define('IS_MIGRATION_RUNNING', (file_exists(MIGRATION_FILE) ? true : false));

//-- LOG FILES
define('SYSTEM_LOG', LOGS_PATH . 'system/app.log');
define('MIGRATION_LOG', LOGS_PATH . 'system/migrations.log');
define('CRON_HOUSEKEEPER_LOG', LOGS_PATH . 'system/cron-housekeeper.log');
define('LOG_ROTATE_SIZE', 2); //-- MB UNTIL ROTATE
define('LOG_LINES_PER_PAGE', 1000);
