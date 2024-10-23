<?php

/*
----------------------------------
 ------  Created: 102324   ------
 ------  Austin Best	   ------
----------------------------------
*/

if (!$_SESSION) {
    session_start();
}

if (!$_SESSION['IN_UI']) {
    exit('Invalid session, refresh the page');
}

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
