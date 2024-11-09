<?php

/*
----------------------------------
 ------  Created: 102324   ------
 ------  Austin Best	   ------
----------------------------------
*/

error_reporting(E_ERROR | E_PARSE);

if (!$_SESSION) {
    session_start();
}

if (!$_SESSION['IN_UI']) {
    exit('Invalid session, refresh the page');
}

require '../loader.php';

if ($_POST['m'] == 'openAppAccessLog') {
    getLog($_POST['appName'], intval($_POST['page']), true);
}

if ($_POST['m'] == 'viewLog') {
    getLog($_POST['log'], intval($_POST['page']));
}

if ($_POST['m'] == 'deleteLog') {
    unlink(LOGS_PATH . $_POST['log']);
}
