<?php

/*
----------------------------------
 ------  Created: 102124   ------
 ------  Austin Best	   ------
----------------------------------
*/

$logList = getLogs();

?>

<div class="row p3">
    <div class="col-sm-2">
        <?php
        $index = 0;
        ?><h4 class="mt-2 fst-italic">system</h4><?php
        foreach ($logList['system'] as $log) {
            $index++;
            ?>
            <ul style="margin-bottom: 0px; padding-bottom: 0px;">
                <li>
                    <i class="far fa-trash-alt text-danger" title="Delete access log" style="cursor:pointer;" onclick=""></i> 
                    <span id="app-log-<?= $index ?>" style="cursor:pointer;" onclick="viewLog('<?= $log ?>', <?= $index ?>)"><?= $log ?></span>
                </li>
            </ul>
            <?php
        }

        foreach ($logList as $app => $logs) {
            if ($app == 'system') {
                continue;
            }

            ?><h4 class="mt-2"><?= $app ?></h4><?php
            foreach ($logs as $log) {
                $index++;
                ?>
                <ul style="margin-bottom: 0px; padding-bottom: 0px;">
                    <li>
                        <i class="far fa-trash-alt text-danger" title="Delete access log" style="cursor:pointer;" onclick="deleteLog('<?= $log ?>')"></i> 
                        <span id="app-log-<?= $index ?>" style="cursor:pointer;" onclick="viewLog('<?= $log ?>', <?= $index ?>)"><?= str_replace('access_', '', $log) ?></span>
                    </li>
                </ul>
                <?php
            }
        }
        ?>
    </div>
    <div class="col-sm-10">
        <div id="log-viewer" class="mt-3"></div>
    </div>
</div>
