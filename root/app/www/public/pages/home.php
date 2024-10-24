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

$getTotalAppStats       = getTotalAppStats($settingsFile);
$getTotalEndpointStats  = getTotalEndpointStats($settingsFile);
$getTotalUsageStats     = getTotalUsageStats($settingsFile, $usageFile);
?>

<div class="card mb-3">
    <div class="card-header">Purpose</div>
    <div class="card-body">
        <p class="card-text">
            The list of 3<sup>rd</sup> party apps that utilize the starr app API's is ever growing but their is no limitation to what they can do and access! Most apps need very 
            little access to function so why expose every method available and full access to your database needlessly?<br><br>

            Permission scopes for apikeys is something that would be better served native in the apps but as it stands that request was denied years ago so it was time to simply make it 
            possible with another solution.
        </p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">Protection</div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12 col-lg-3">
                <div class="card mb-3">
                    <div class="card-header">Starr instances</div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <td></td>
                                <td>Starr</td>
                                <td>Instances</td>
                            </tr>
                            <?php
                            if ($getTotalAppStats) {
                                foreach ($getTotalAppStats as $starr => $instances) {
                                    ?>
                                    <tr>
                                        <td><img src="images/logos/<?= $starr ?>.png" style="height:20px;"></td>
                                        <td><?= ucfirst($starr) ?></td>
                                        <td><?= $instances ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?><td colspan="3">Nothing protected! What are you waiting for?</td><?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-3">
                <div class="card mb-3">
                    <div class="card-header">Starr app endpoints</div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <td></td>
                                <td>Starr</td>
                                <td>Apps</td>
                                <td>Enabled</td>
                                <td>Disabled</td>
                            </tr>
                            <?php
                            if ($getTotalEndpointStats) {
                                foreach ($getTotalEndpointStats as $starr => $endpointStats) {
                                    ?>
                                    <tr>
                                        <td><img src="images/logos/<?= $starr ?>.png" style="height:20px;"></td>
                                        <td><?= ucfirst($starr) ?></td>
                                        <td><?= number_format($endpointStats['apps']) ?></td>
                                        <td><?= number_format($endpointStats['allowed']) ?></td>
                                        <td><?= number_format($endpointStats['total'] - $endpointStats['allowed']) ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?><td colspan="5">Nothing protected! What are you waiting for?</td><?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-3">
                <div class="card mb-3">
                    <div class="card-header">Starr api enforcement</div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <td></td>
                                <td>Starr</td>
                                <td>Approved</td>
                                <td>Rejected</td>
                            </tr>
                            <?php
                            if ($getTotalUsageStats) {
                                foreach ($getTotalUsageStats as $starr => $usageStats) {
                                    ?>
                                    <tr>
                                        <td><img src="images/logos/<?= $starr ?>.png" style="height:20px;"></td>
                                        <td><?= ucfirst($starr) ?></td>
                                        <td><?= number_format($usageStats['success']) ?></td>
                                        <td><?= number_format($usageStats['rejected']) ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?><td colspan="4">Nothing protected! What are you waiting for?</td><?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
