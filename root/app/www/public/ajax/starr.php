<?php

/*
----------------------------------
 ------  Created: 102324   ------
 ------  Austin Best	   ------
----------------------------------
*/

require '../loader.php';

if ($_POST['m'] == 'testStarr') {
    $test = testStarrConnection($app, $_POST['url'], $_POST['apikey']);

    $error = $result = '';
    if ($test['code'] != 200) {
        $error = 'Failed to connect with code: ' . $test['code'];
    } else {
        $result = 'Connection successful to ' . $app . ': Instance ' . $test['response']['instanceName'];
    }

    echo json_encode(['error' => $error, 'result' => $result]);
}

if ($_POST['m'] == 'deleteStarr') {
    unset($settingsFile[$app][$_POST['instance']]);
    setFile(APP_SETTINGS_FILE, $settingsFile);
}

if ($_POST['m'] == 'saveStarr') {
    //-- SOME BASIC SANITY CHECKING
    if (!str_contains($_POST['url'], 'http')) {
        $_POST['url'] = 'http://' . $_POST['url'];
    }

    $_POST['url'] = rtrim($_POST['url'], '/');

    //-- GET THE INSTANCE NAME
    $test = testStarrConnection($app, $_POST['url'], $_POST['apikey']);
    $name = 'ERROR';

    if ($test['code'] == 200) {
        $name = $test['response']['instanceName'];
    }

    //-- NEW INSTANCE
    if ($_POST['instance'] == '99') {
        $settingsFile[$app][] = ['name' => $name, 'url' => $_POST['url'], 'apikey' => $_POST['apikey'], 'username' => rawurldecode($_POST['username']), 'password' => rawurldecode($_POST['password'])];
    } else {
        if ($_POST['instance'] >= 0) {
            $settingsFile[$app][$_POST['instance']] = ['name' => $name, 'url' => $_POST['url'], 'apikey' => $_POST['apikey'], 'username' => rawurldecode($_POST['username']), 'password' => rawurldecode($_POST['password'])];
        }
    }

    setFile(APP_SETTINGS_FILE, $settingsFile);
}

if ($_POST['m'] == 'newAppStarrAccess') {
    $existing       = $_POST['id'] != 99 ? $settingsFile['access'][$app][$_POST['id']] : [];
    $clone          = isset($_POST['clone']) ? $settingsFile['access'][$app][$_POST['clone']] : [];
    $endpoints      = getStarrEndpoints($app);
    $appInstances   = '';

    if ($clone) {
        $existing = $clone;
        unset($existing['apikey']);
        $existing['name'] .= ' - Clone';
    }

    if ($settingsFile[$app]) {
        foreach ($settingsFile[$app] as $instance => $instanceSettings) {
            $appInstances .= '<option ' . (isset($existing['instances']) && $instance == $existing['instances'] ? 'selected ' : '') . 'value="' . $instance . '">' . $instanceSettings['name'] . ' (' . $instanceSettings['url'] . ')</option>';
        }
    }

    ?>
    <?php if ($clone) { ?>
        <center><h4>Cloning: <span class="text-warning"><?= $clone['name'] ?></span></h4></center>
    <?php } ?>
    <table class="table table-bordered table-hover">
        <tr>
            <td class="w-50">App<br><span class="text-small">The app that needs access to the <?= ucfirst($app) ?> API</span></td>
            <td><input type="text" class="form-control" placeholder="Notifiarr" id="access-name" value="<?= $existing['name'] ?>"></td>
        </tr>
        <tr>
            <td>Apikey<br><span class="text-small">The apikey used to negotiate between the app and the starr proxy</span></td>
            <td><input type="text" class="form-control" id="access-apikey" value="<?= $existing['apikey'] ?: generateApikey() ?>"></td>
        </tr>
        <tr>
            <td><?= ucfirst($app) ?> instance<br><span class="text-small">Select which instance this app will access</span></td>
            <td>
                <select class="form-select" id="access-instances"><option value="">-- Select instance --</option><?= $appInstances ?></select>
            </td>
        </tr>
        <tr>
            <td>Endpoint template<br><span class="text-small">Automatically select the endpoints based on an app template</span></td>
            <td>
                <select class="form-select" id="access-template" onchange="applyTemplateOptions()"><?= getTemplateOptions() ?></select>
            </td>
        </tr>
        <tr>
            <td>
                <?= ucfirst($app) ?> endpoints<br>
                <span class="text-small">
                    Check all: 
                        <span class="text-info" style="cursor: pointer;" onclick="$('.endpoint-get').prop('checked', true)">get</span>,
                        <span class="text-info" style="cursor: pointer;" onclick="$('.endpoint-post').prop('checked', true)">post</span>,
                        <span class="text-info" style="cursor: pointer;" onclick="$('.endpoint-put').prop('checked', true)">put</span>,
                        <span class="text-info" style="cursor: pointer;" onclick="$('.endpoint-delete').prop('checked', true)">delete</span><br>
                    Uncheck all: 
                        <span class="text-info" style="cursor: pointer;" onclick="$('.endpoint-get').prop('checked', false)">get</span>,
                        <span class="text-info" style="cursor: pointer;" onclick="$('.endpoint-post').prop('checked', false)">post</span>,
                        <span class="text-info" style="cursor: pointer;" onclick="$('.endpoint-put').prop('checked', false)">put</span>,
                        <span class="text-info" style="cursor: pointer;" onclick="$('.endpoint-delete').prop('checked', false)">delete</span><br>
                </span>
            </td>
            <td>
                <table class="table table-hover">
                    <?php
                    $counter = 1;
                    foreach ($endpoints as $endpoint => $endpointInfo) {
                        if (!$endpointInfo['label']) {
                            continue;
                        }

                        ?>
                        <tr class="table-primary">
                            <td><?= $endpointInfo['label'] ?><br><span class="text-small"><?= $endpoint ?></span></td>
                            <td>
                                <?php
                                foreach ($endpointInfo['methods'] as $method) {
                                    if (!$method) {
                                        continue;
                                    }

                                    $checked = is_array($existing['endpoints']) && is_array($existing['endpoints'][$endpoint]) && in_array($method, $existing['endpoints'][$endpoint]) ? 'checked' : '';
                                    ?>
                                    <div class="form-check form-switch">
                                        <input <?= $checked ?> id="endpoint-counter-<?= $counter ?>" data-endpoint="<?= $endpoint ?>" data-method="<?= $method ?>" type="checkbox" class="form-check-input endpoint-<?= $method ?>">
                                        <label for="endpoint-counter-<?= $counter ?>"><?= $method ?></label>
                                    </div>
                                    <?php
                                    $counter++;
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </td>
        </tr>
    </table>
    <div class="bg-dark w-100 p-3 text-center" style="position: sticky; bottom: 0px;">
        <button class="btn btn-success" onclick="saveAppStarrAccess('<?= $app ?>', <?= $_POST['id'] ?>)"><i class="far fa-save"></i> Enable access</button>
    </div>
    <?php
}

if ($_POST['m'] == 'saveAppStarrAccess') {
    $endpoints = [];
    foreach ($_POST as $key => $val) {
        if (!str_contains($key, 'endpoint-')) {
            continue;
        }

        $id = str_replace('endpoint-', '', $key);
        if (!$_POST['enabled-' . $id]) {
            continue;
        }

        $endpoints[$val][] = $_POST['method-' . $id];
    }

    $access['name']         = $_POST['name'];
    $access['apikey']       = $_POST['apikey'];
    $access['instances']    = $_POST['instances'];
    $access['endpoints']    = $endpoints;

    if ($_POST['id'] != 99) {
        $access['usage'] = $settingsFile['access'][$app][$_POST['id']]['usage'];
        $settingsFile['access'][$app][$_POST['id']] = $access;
    } else {
        $settingsFile['access'][$app][] = $access;
    }

    setFile(APP_SETTINGS_FILE, $settingsFile);
}

if ($_POST['m'] == 'deleteAppStarrAccess') {
    unset($settingsFile['access'][$app][$_POST['id']]);
    setFile(APP_SETTINGS_FILE, $settingsFile);
}

if ($_POST['m'] == 'resetUsage') {
    unset($usageFile[$app][$_POST['id']]);
    setFile(APP_USAGE_FILE, $usageFile);
}

if ($_POST['m'] == 'addEndpointAccess') {
    $settingsFile['access'][$app][$_POST['id']]['endpoints'][$_POST['endpoint']][] = $_POST['method'];
    print_r($settingsFile['access'][$app][$_POST['id']]);
    setFile(APP_SETTINGS_FILE, $settingsFile);
}
