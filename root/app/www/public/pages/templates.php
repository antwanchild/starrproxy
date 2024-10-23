<?php

/*
----------------------------------
 ------  Created: 101124   ------
 ------  Austin Best	   ------
----------------------------------
*/

if (!$_SESSION) {
    session_start();
}

if (!$_SESSION['IN_UI']) {
    exit('Invalid session, refresh the page');
}

$templateList = getTemplateList();
?>

<div class="row p3">
    <div class="col-sm-1">
    <?php
    $index = 0;
    foreach ($templateList as $app => $appTemplates) {
        ?><h4 class="mt-2"><?= $app ?></h4><?php
        foreach ($appTemplates as $appTemplate) {
            $index++;
            $custom = str_contains($appTemplate['location'], APP_USER_TEMPLATES_PATH);
            ?>
            <ul style="margin-bottom: 0px; padding-bottom: 0px;">
                <li class="app-index-<?= $index ?>">
                    <span style="cursor: pointer;" onclick="viewTemplate('<?= $appTemplate['location'] . $appTemplate['starr'] ?>/<?= $app ?>.json', <?= $index ?>)"><?= $appTemplate['starr'] ?></span>
                    <?= $custom ? ' <i class="far fa-user text-warning" title="Custom user template"></i> <i class="far fa-trash-alt text-danger" title="Delete custom template" style="cursor:pointer;" onclick="deleteCustomTemplate(\'' . $app . '\', \'' . $appTemplate['starr'] . '\')"></i>' : '' ?>
                </li>
            </ul>
            <?php
        }
    }
    ?>
    </div>
    <div class="col-sm-11">
        <div id="template-viewer" class="mt-3"></div>
    </div>
</div>
