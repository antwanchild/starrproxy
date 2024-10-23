<?php

/*
----------------------------------
 ------  Created: 101124   ------
 ------  Austin Best	   ------
----------------------------------
*/

require 'loader.php';
require ABSOLUTE_PATH . 'includes/header.php';

?>
<div class="row w-100 m-5">
    <?php 
    if (in_array($app, $starrApps)) {
        ?>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
            <li class="breadcrumb-item active"><?= ucfirst($app) ?></li>
        </ol>
        <?php
        require ABSOLUTE_PATH . 'pages/starr.php';
    } elseif ($page) {
        ?>
        <ol class="breadcrumb">
            <?php if ($page != 'home') { ?>
            <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
            <li class="breadcrumb-item active"><?= ucfirst($page) ?></li>
            <?php } else { ?>
            <li class="breadcrumb-item active">Home</li>
            <?php } ?>
        </ol>
        <?php
        require ABSOLUTE_PATH . 'pages/' . $page . '.php';
    } else {
        ?>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Home</li>
        </ol>
        <?php
        require ABSOLUTE_PATH . 'pages/home.php';
    }
    ?>
</div>
<?php

require ABSOLUTE_PATH . 'includes/footer.php';
