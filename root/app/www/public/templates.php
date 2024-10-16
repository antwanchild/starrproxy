<?php

/*
----------------------------------
 ------  Created: 101124   ------
 ------  Austin Best	   ------
----------------------------------
*/

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
                <li class="app-index-<?= $index ?>" style="cursor: pointer;" onclick="viewTemplate('<?= $appTemplate['location'] . $appTemplate['starr'] ?>/<?= $app ?>.json', <?= $index ?>)">
                    <?= $appTemplate['starr'] ?>
                    <?= $custom ? ' <i class="far fa-user text-warning" title="Custom user template"></i>' : '' ?>
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
