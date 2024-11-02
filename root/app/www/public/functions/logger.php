<?php

/*
----------------------------------
 ------  Created: 101124   ------
 ------  Austin Best	   ------
----------------------------------
*/

function logger($logfile, $apikey = '', $endpoint = '', $proxyCode = 200, $starrCode = 0, $starrRequest = [])
{
    if (!$logfile) {
        return;
    }

    //-- ROTATE IT DAILY
    if (file_exists($logfile) && date('Ymd') != date('Ymd', filemtime($logfile))) {
        rename($logfile, str_replace('.log', '_' . date('Ymd') . '.log', $logfile));
    }

    $log = date('c') . ' ua:' . $_SERVER['HTTP_USER_AGENT'];
    if ($apikey) {
        $log .= '; key:' . truncateMiddle($apikey, 20);
    }
    if ($endpoint) {
        $log .= '; endpoint:' . $endpoint;
    }

    $log .= '; method:' . strtolower($_SERVER['REQUEST_METHOD']);
    $log .= '; proxyCode:' . $proxyCode;

    if ($starrCode) {
        $log .= '; starrCode:' . $starrCode;

        if ($starrCode != 200) {
            $log .= '; starrResponse:' . json_encode($starrRequest);
        }
    }

    file_put_contents($logfile, $log . "\n", FILE_APPEND);
}

function getLogs()
{
    if (is_dir(APP_LOG_PATH)) {
        $dir = opendir(APP_LOG_PATH);
        while ($log = readdir($dir)) {
            if (!str_contains($log, '.log')) {
                continue;
            }

            $logfile = str_replace('.log', '', $log);
            list($access, $app, $date) = explode('_', $logfile);

            if ($app && !is_numeric($app)) {
                $list[$app][] = $logfile . '.log';
                krsort($list[$app]);
            } else {
                $list['system'][] = $logfile . '.log';
                krsort($list['system']);
            }
        }
        closedir($dir);
    }

    ksort($list);
    return $list;
}

function getLog($log, $app = false)
{
    $logfile    = file_exists(APP_LOG_PATH . 'access_' . $log . '.log') ? APP_LOG_PATH . 'access_' . $log . '.log' : APP_LOG_PATH . $log;
    $file       = file_get_contents($logfile);
    $lines      = explode("\n", $file);

    ?>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#access" aria-selected="true" role="tab">Access log</a>
        </li>
        <?php if ($app) { ?>
        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#endpoints" aria-selected="false" tabindex="-1" role="tab">Endpoint usage</a>
        </li>
        <?php } ?>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade show active" id="access" role="tabpanel">
            <table class="table table-bordered table-hover">
                <?php
                if ($lines) {
                    $endpointUsage  = [];
                    foreach ($lines as $line) {
                        $error = '';
                        if (str_contains_any($line, ['Code:3', 'Code:4', 'Code:5'])) {
                            $error = '<span class="text-danger">[ERROR]</span> ';
                        }

                        if (!str_contains($line, 'key:' . $_POST['key'])) {
                            continue;
                        }

                        preg_match('/endpoint:(.*);/U', $line, $endpointMatch);
                        preg_match('/method:(.*);/U', $line, $methodMatch);
                        if ($endpointMatch[1]) {
                            $endpointUsage[$endpointMatch[1]][$methodMatch[1]]++;
                        }

                        ?><tr><td><?= $error . $line ?></td></tr><?php
                    }
                } else {
                    ?><tr><td>No log data found.</td></tr><?php   
                }
                ?></table>
        </div>
        <?php 
        if ($app) {
            $proxiedApp = getAppFromProxiedKey($_POST['key'], true);
            ?>
            <div class="tab-pane fade" id="endpoints" role="tabpanel">
                <h4>Endpoint usage <span class="text-small">(<?= count($endpointUsage) ?> endpoint<?= count($endpointUsage) == 1 ? '' : 's' ?>)</span></h4>
                <?php
                foreach ($endpointUsage as $endpoint => $methods) {
                    foreach ($methods as $method => $usage) {
                        $accessError = true;

                        if ($proxiedApp['access'][$endpoint] || $proxiedApp['access'][strtolower($endpoint)]) {
                            if (in_array(strtolower($method), $proxiedApp['access'][$endpoint]) || in_array(strtolower($method), $proxiedApp['access'][strtolower($endpoint)])) {
                                $accessError = false;
                            }
                        }

                        ?>
                            <i id="disallowed-endpoint-<?= md5($endpoint.$method) ?>" class="far fa-times-circle text-danger" title="Disallowed endpoint, click to allow it" style="display: <?= $accessError ? 'inline-block' : 'none' ?>; cursor: pointer;" onclick="addEndpointAccess('<?= $app ?>', <?= $_POST['accessId'] ?>, '<?= $endpoint ?>', '<?= $method ?>', '<?= md5($endpoint.$method) ?>')"></i> 
                            <i id="allowed-endpoint-<?= md5($endpoint.$method) ?>" class="far fa-check-circle text-success" title="Allowed endpoint" style="display: <?= !$accessError ? 'inline-block' : 'none' ?>;"></i>
                            [<?= strtoupper($method) ?>] <?= $endpoint . ': ' . number_format($usage) ?> hit<?= $usage == 1 ? '' : 's' ?><br>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
