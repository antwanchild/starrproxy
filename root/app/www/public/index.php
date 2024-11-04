<?php

/*
----------------------------------
 ------  Created: 101124   ------
 ------  Austin Best	   ------
----------------------------------
*/

error_reporting(E_ERROR | E_PARSE);

if (!$_SESSION) {
    session_start();
}

$_SESSION['IN_UI'] = true;

require 'loader.php';
require ABSOLUTE_PATH . 'includes/header.php';

?>
<div class="row w-100 m-5">
    <ol class="breadcrumb">
        <li class="breadcrumb-item <?= !$page || $page == 'home' ? 'active' : '' ?>"><?= $page != 'home' ? '<a href="?page=home">Home</a>' : 'Home' ?></li>
        <?php
        switch (true) {
            case in_array($app, StarrApps::LIST):
                $requiredPage = 'starr';
                ?><li class="breadcrumb-item active"><?= ucfirst($app) ?></li><?php
                break;
            case $page:
                $requiredPage = $page;
                if ($page != 'home')  { 
                    ?><li class="breadcrumb-item active"><?= ucfirst($page) ?></li><?php 
                }
                break;
            default:
                $requiredPage = 'home';
                break;
        }
        ?>
    </ol>
    <?php require ABSOLUTE_PATH . 'pages/' . $requiredPage . '.php'; ?>
</div>
<?php

require ABSOLUTE_PATH . 'includes/footer.php';
