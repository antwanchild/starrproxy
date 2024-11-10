<?php

/*
----------------------------------
 ------  Created: 111024   ------
 ------  Austin Best	   ------
----------------------------------
*/

//-- RESET THE LIST
$q = [];

//-- ALWAYS NEED TO BUMP THE MIGRATION ID
$q[] = "UPDATE " . SETTINGS_TABLE . "
        SET value = '004'
        WHERE name = 'migration'";

$q[] = "ALTER TABLE " . APPS_TABLE . " ADD template TEXT NULL";

foreach ($q as $query) {
	logger(MIGRATION_LOG, '<span class="text-success">[Q]</span> ' . preg_replace('!\s+!', ' ', $query));

    $proxyDb->query($query);

	if ($proxyDb->error() != 'not an error') {
		logger(MIGRATION_LOG, '<span class="text-info">[R]</span> ' . $proxyDb->error(), 'error');
	} else {
		logger(MIGRATION_LOG, '<span class="text-info">[R]</span> query applied!');
	}
}
