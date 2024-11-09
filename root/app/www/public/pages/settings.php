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

$migrations = '<option value="000">000_fresh_start</option>';
$dir = opendir(MIGRATIONS_PATH);
while ($migration = readdir($dir)) {
    if (str_contains($migration, '.php')) {
        $migrations .= '<option ' . ($settingsTable['migration'] == substr($migration, 0, 3) ? 'selected ' : '') . 'value="' . substr($migration, 0, 3) . '">' . str_replace('.php', '', $migration) . '</option>';
    }
}
closedir($dir);

?>
<div class="w-100 mb-2">
    <button class="btn btn-outline-success border-light" onclick="saveSettings()"><i class="far fa-save"></i> Save</button>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="card border-default mb-3">
            <div class="card-header">System</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <td class="w-25">
                                    API key<br>
                                    <span class="text-small">File: <?= APP_APIKEY_FILE ?></span>
                                </td>
                                <td>
                                    <div class="input-group mb-3 w-25">
                                        <input type="text" class="form-control" aria-describedby="apikey-input" value="<?= APP_APIKEY ?>" id="setting-apikey">
                                        <button title="Copy" class="btn btn-primary" type="button" id="apikey-input" onclick="clipboard('setting-apikey', 'val')"><i class="far fa-copy"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card border-default mb-3">
            <div class="card-header">Database</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <td class="w-25">
                                    Migration<br>
                                    <span class="text-small">Newest: <?= $proxyDb->getNewestMigration() ?></span>
                                </td>
                                <td>
                                    <select class="form-select w-50" id="setting-migration"><?= $migrations ?></select>
                                    <span class="text-small">If you change this, refresh the page after you click save.</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Backup retention length<br>
                                    <span class="text-small">Default: <?= BACKUP_AGE ?></span>
                                </td>
                                <td><input type="number" class="form-control d-inline-block w-25" id="setting-backupRetentionLength" value="<?= BACKUP_AGE ?>"> days</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card border-default mb-3">
            <div class="card-header">Logging</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <tbody>
                            <tr>
                                <td class="w-25">
                                    Rotation size<br>
                                    <span class="text-small">Default: <?= LOG_ROTATE_SIZE ?></span>
                                </td>
                                <td><input type="number" class="form-control d-inline-block w-25" id="setting-logRotationSize" value="<?= LOG_ROTATE_SIZE ?>">MiB</td>
                            </tr>
                            <tr>
                                <td>
                                    Log retention length<br>
                                    <span class="text-small">Default: <?= LOG_AGE ?></span>
                                </td>
                                <td><input type="number" class="form-control d-inline-block w-25" id="setting-logRetentionLength" value="<?= LOG_AGE ?>"> days</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
