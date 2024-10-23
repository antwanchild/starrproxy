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

if ($_POST['m'] == 'openTemplateStarrAccess') {
    ?>
    <table class="table table-bordered table-hover">
        <tr>
            <td>Path</td>
            <td><?= APP_USER_TEMPLATES_PATH . $_POST['app'] ?>/*.json</td>
        </tr>
        <tr>
            <td>Name<br><span class="text-small">[a-zA-Z0-9 _-]</span></td>
            <td><input id="new-template-name" type="text" class="form-control" placeholder="notifiarr"></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><button class="btn btn-outline-success" onclick="saveTemplateStarrAccess('<?= $_POST['app'] ?>', <?= $_POST['id'] ?>)">Add template</button></td>
        </tr>
    </table>
    Notes:<br>
    <ul>
        <li>Using an existing template name will overwrite it</li>
    </ul>
    <?php
}

if ($_POST['m'] == 'saveTemplateStarrAccess') {
    $endpoints  = $settingsFile['access'][$app][$_POST['id']]['endpoints'];
    $name       = strtolower(preg_replace('/[^a-zA-Z0-9 _-]/', '', $_POST['name']));
    file_put_contents(APP_USER_TEMPLATES_PATH . $app . '/' . $name . '.json', json_encode($endpoints, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

if ($_POST['m'] == 'viewTemplate') {
    $template = file_get_contents(ABSOLUTE_PATH . $_POST['template']);
    list($starr, $app) = explode('/', $_POST['template']);
    ?>
    <pre><i class="far fa-copy fa-2x text-info" style="cursor: pointer; float: right;" onclick="clipboard('template-json', 'html')" title="Copy template to clipboard"></i><span id="template-json"><?= $template ?></span></pre>
    <?php
}

if ($_POST['m'] == 'applyTemplateOptions') {
    echo file_get_contents(ABSOLUTE_PATH . $_POST['template']);
}

if ($_POST['m'] == 'deleteCustomTemplate') {
    unlink(APP_USER_TEMPLATES_PATH . $_POST['starr'] . '/' . $_POST['app'] . '.json');
}
