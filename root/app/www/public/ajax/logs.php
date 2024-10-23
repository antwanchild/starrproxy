<?php

/*
----------------------------------
 ------  Created: 102324   ------
 ------  Austin Best	   ------
----------------------------------
*/

require '../loader.php';

if ($_POST['m'] == 'openAppAccessLog') {
    getLog($_POST['accessApp'], $app);
}

if ($_POST['m'] == 'viewLog') {
    getLog($_POST['log']);
}

if ($_POST['m'] == 'deleteLog') {
    unlink(APP_LOG_PATH . $_POST['log']);
}
