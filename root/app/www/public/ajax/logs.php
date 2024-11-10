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

if ($_POST['m'] == 'viewAppLog') {
    getLog($_POST['log'], 1, true);
}

if ($_POST['m'] == 'viewLog') {
    getLog($_POST['log'], intval($_POST['page']));
}

if ($_POST['m'] == 'deleteLog') {
    unlink($_POST['log']);
}
