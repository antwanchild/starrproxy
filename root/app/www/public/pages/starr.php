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
?>

<div class="col-sm-12">
    <h4><?= $appLabel ?> instances</h4>
    <div class="table-responsive">
        <table class="table table-bordered" style="min-width: 750px;" align="center">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Apikey</th>
                    <th><i class="far fa-question-circle" title="This is only needed for corruption checks with Notifiarr"></i> User</th>
                    <th><i class="far fa-question-circle" title="This is only needed for corruption checks with Notifiarr"></i> Pass</th>
                    <th class="w-25">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($starrsTable) {
                    foreach ($starrsTable as $starrInstance) {
                        if ($starrInstance['starr'] != $starr->getStarrInterfaceIdFromName($app)) {
                            continue;
                        }

                        $test       = $starr->testConnection($app, $starrInstance['url'], $starrInstance['apikey']);
                        $version    = $test['responseHeaders']['X-Application-Version'][0];
                        $branch     = $test['response']['branch'];

                        if ($test['response']['instanceName'] != $starrInstance['name']) {
                            $proxyDb->updateStarrAppSetting($starrInstance['id'], 'name', $test['response']['instanceName']);
                            $starrInstance['name'] = $test['response']['instanceName'];
                        }

                        ?>
                        <tr>
                            <td>
                                <?= $starrInstance['name'] ?><br>
                                <span class="text-small"><?= $branch ?> → v<?= $version ?></span>
                            </td>
                            <td><input type="text" class="form-control" id="instance-url-<?= $starrInstance['id'] ?>" placeholder="http://localhost:1111" value="<?= $starrInstance['url'] ?>"></td>
                            <td>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="instance-apikey-<?= $starrInstance['id'] ?>" data-apikey="<?= $starrInstance['apikey'] ?>" placeholder="12345-67890-09876-54321" value="<?= truncateMiddle($starrInstance['apikey'], 20) ?>" aria-describedby="apikey-<?= $starrInstance['id'] ?>">
                                    <button class="btn btn-primary" type="button" id="apikey-<?= $starrInstance['id'] ?>" onclick="$('#instance-apikey-<?= $starrInstance['id'] ?>').val($('#instance-apikey-<?= $starrInstance['id'] ?>').data('apikey'))">Show</button>
                                </div>
                            </td>
                            <td><input type="text" class="form-control" id="instance-username-<?= $starrInstance['id'] ?>" placeholder="username" value="<?= $starrInstance['username'] ?>"></td>
                            <td><input type="password" class="form-control" id="instance-password-<?= $starrInstance['id'] ?>" placeholder="password" value="<?= $starrInstance['password'] ?>"></td>
                            <td align="center">
                                <button class="btn btn-outline-info" type="button" onclick="testStarr('<?= $starrInstance['id'] ?>', '<?= $app ?>')"><i class="fas fa-network-wired"></i> Test API</button>
                                <button class="btn btn-outline-success" type="button" onclick="saveStarr('<?= $starrInstance['id'] ?>', '<?= $app ?>')"><i class="fas fa-save"></i> Save</button>
                                <button class="btn btn-outline-danger" type="button" onclick="deleteStarr('<?= $starrInstance['id'] ?>', '<?= $app ?>')"><i class="fas fa-trash-alt"></i> Delete</button>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <tr>
                    <td></td>
                    <td><input type="text" class="form-control" id="instance-url-99" placeholder="http://localhost:1111"></td>
                    <td><input type="text" class="form-control" id="instance-apikey-99" placeholder="12345-67890-09876-54321"></td>
                    <td><input type="text" class="form-control" id="instance-username-99" placeholder="username"></td>
                    <td><input type="text" class="form-control" id="instance-password-99" placeholder="password"></td>
                    <td align="center">
                        <button class="btn btn-outline-info" type="button" onclick="testStarr('99', '<?= $app ?>')"><i class="fas fa-network-wired"></i> Test API</button>
                        <button class="btn btn-outline-success" type="button" onclick="saveStarr('99', '<?= $app ?>')"><i class="fas fa-plus-circle"></i> Add</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="col-sm-12">
    <h4>3<sup>rd</sup> party app access</h4>
    <br>You will use <code id="proxyUrl"><?= APP_URL ?></code> <i class="far fa-copy text-info" style="cursor: pointer;" onclick="clipboard('proxyUrl', 'html')" title="Copy apikey to clipboard"></i> as the <?= ucfirst($app) ?> url in the 3<sup>rd</sup> party app and copy the apikey below<br><br>
    <div class="row">
        <?php
        if ($appsTable) {
            foreach ($appsTable as $accessApp) {
                $template = '';
                $parentStarrApp = $proxyDb->getStarrAppFromId($accessApp['starr_id'], $starrsTable);

                if ($app != $starr->getStarrInterfaceNameFromId($parentStarrApp['starr'])) {
                    continue;
                }

                $accessApp['endpoints'] = json_decode($accessApp['endpoints'], true) ?: [];
                $usage = $usageDb->getStarrAppUsage($accessApp['id']);

                $templateFile = file_exists($accessApp['template']) ? $accessApp['template'] : str_replace('../', './', $accessApp['template']);
                if (file_exists($templateFile)) {
                    $templateEndpoints = getFile($templateFile);
                    $template = ', <span ' . (count($accessApp['endpoints']) != count($templateEndpoints) ? 'class="text-warning" title="Template does not match, click to fix that" style="cursor: pointer;" onclick="viewAppEndpointDiff(' . $accessApp['id'] . ')"' : '') . '>Template: ' . count($templateEndpoints) . ' endpoint' . (count($templateEndpoints) == 1 ? '' : 's') . '</span>';
                }
                ?>
                <div class="col-sm-12 col-lg-3">
                    <div class="card border-secondary mb-3">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    App: <?= $accessApp['name'] ?>
                                </div>
                                <div class="col-sm-6 text-end">
                                    <ul style="list-style-type: none;">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h text-info"></i></a>
                                            <div class="dropdown-menu">
                                                <div class="ms-2">
                                                    <span style="cursor: pointer;" onclick="openAppStarrAccess('<?= $app ?>', <?= $accessApp['id'] ?>)" title="Modify the <?= $accessApp['name'] ?> app's details"><i class="far fa-edit"></i> Modify</span><br>
                                                    <span style="cursor: pointer;" onclick="viewAppLog('<?= LOGS_PATH . 'access_'. $accessApp['name'] .'.log' ?>', '<?= truncateMiddle($accessApp['apikey'], 20) ?>', '<?= $accessApp['name'] ?>')" title="View <?= $accessApp['name'] ?> app logs"><i class="fas fa-newspaper"></i> Logs</span><br>
                                                    <span style="cursor: pointer;" onclick="openAppStarrAccess('<?= $app ?>', 99, <?= $accessApp['id'] ?>)" title="Clone the <?= $accessApp['name'] ?> app"><i class="far fa-clone"></i> Clone</span><br>
                                                    <span style="cursor: pointer;" onclick="openTemplateStarrAccess('<?= $app ?>', <?= $accessApp['id'] ?>)" title="Create a new template based on <?= $accessApp['name'] ?>'s settings"><i class="far fa-file-alt"></i> Create template</span><br>
                                                    <div class="dropdown-divider"></div>
                                                    <span style="cursor: pointer;" onclick="resetUsage('<?= $app ?>', <?= $accessApp['id'] ?>)" title="Reset usage counter"><i class="fas fa-recycle text-danger"></i> Reset usage</span><br>
                                                    <span style="cursor: pointer;" onclick="deleteAppStarrAccess('<?= $app ?>', <?= $accessApp['id'] ?>)" title="Remove the <?= $accessApp['name'] ?> app's access"><i class="far fa-trash-alt text-danger"></i> Delete</span>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            Instance: <?= $parentStarrApp['name'] ?> <span class="text-small"><?= $parentStarrApp['url'] ?></span><br>
                            Access: <?= count($accessApp['endpoints']) ?> endpoint<?= count($accessApp['endpoints']) == 1 ? '' : 's' ?><?= $template ?><br>
                            Apikey: <?= truncateMiddle($accessApp['apikey'], 20) ?> <i class="far fa-copy text-info" style="cursor: pointer;" onclick="clipboard('app-<?= $accessApp['id'] ?>-apikey', 'html')" title="Copy apikey to clipboard"></i><span id="app-<?= $accessApp['id'] ?>-apikey" style="display: none;"><?= $accessApp['apikey'] ?></span><br>
                            Usage: <?= number_format($usage['allowed'] + $usage['rejected']) ?> request<?= $usage['allowed'] + $usage['rejected'] == 1 ? '' : 's' ?> (Allowed: <?= number_format($usage['allowed']) ?> Rejected: <?= number_format($usage['rejected']) ?>)
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
        <div class="col-sm-12 col-lg-3">
            <div class="card border-secondary mb-3">
                <div class="card-header">New app</div>
                <div class="card-body" style="cursor: pointer;" onclick="openAppStarrAccess('<?= $app ?>', 99)">
                    <h5 class="card-title">Give an external app/script access to a radarr instance</h5>
                    <center>
                        <i class="text-info far fa-plus-square fa-5x"></i>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
