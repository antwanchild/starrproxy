<?php

/*
----------------------------------
 ------  Created: 110924   ------
 ------  Austin Best	   ------
----------------------------------
*/

//-- RESET THE LIST
$q = [];

//-- ALWAYS NEED TO BUMP THE MIGRATION ID
$q[] = "UPDATE " . SETTINGS_TABLE . "
        SET value = '003'
        WHERE name = 'migration'";

$q[] = "UPDATE " . NOTIFICATION_PLATFORM_TABLE . "
        SET parameters = '{\"botToken\":{\"label\":\"Bot token\",\"description\":\"The token for your bot (google: how to create a telegram bot via godfather)\",\"type\":\"text\",\"required\":\"true\"}, \"chatId\":{\"label\":\"Chat id\",\"description\":\"The chat id for the channel where messages go, should start with -100 (google: gist nafiesl get-chat-id-for-a-channel)\",\"type\":\"text\",\"required\":\"true\"}}'
        WHERE id = " . NotificationPlatforms::TELEGRAM;

foreach ($q as $query) {
	logger(MIGRATION_LOG, '<span class="text-success">[Q]</span> ' . preg_replace('!\s+!', ' ', $query));

    $proxyDb->query($query);

	if ($proxyDb->error() != 'not an error') {
		logger(MIGRATION_LOG, '<span class="text-info">[R]</span> ' . $proxyDb->error(), 'error');
	} else {
		logger(MIGRATION_LOG, '<span class="text-info">[R]</span> query applied!');
	}
}
