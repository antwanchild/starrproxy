<?php

/*
----------------------------------
 ------  Created: 102324   ------
 ------  Austin Best	   ------
----------------------------------
*/

function getTotalAppStats($settings)
{
    $stats = [];

    if ($settings) {
        foreach ($settings as $app => $appSettings) {
            if ($app == 'access') {
                continue;
            }

            $stats[$app] = count($settings[$app]);
        }

        ksort($stats);
    }

    return $stats;
}

function getTotalEndpointStats($settings)
{
    $stats = [];

    if ($settings) {
        foreach ($settings as $app => $appSettings) {
            if ($app == 'access') {
                continue;
            }

            $allowed        = 0;
            $total          = 0;
            $apps           = 0;
            $totalEndpoints = getStarrEndpoints($app);
            foreach ($settings['access'][$app] as $appSetting) {
                $allowed += count($appSetting['endpoints']);
                $total += count($totalEndpoints);
                $apps++;
            }

            $stats[$app] = ['apps' => $apps, 'total' => $total, 'allowed' => $allowed];
        }

        if ($stats) {
            ksort($stats);
        }
    }

    return $stats;
}

function getTotalUsageStats($settings, $usage)
{
    $stats = [];
    if ($settings && $usage) {
        foreach ($settings as $app => $appSettings) {
            if ($app == 'access') {
                continue;
            }

            $success    = 0;
            $rejected   = 0;

            if ($usage[$app]) {
                foreach ($usage[$app] as $appUsage) {
                    $success += $appUsage['success'];
                    $rejected += $appUsage['error'];
                }
            }
            $stats[$app] = ['success' => $success, 'rejected' => $rejected];
        }

        if ($stats) {
            ksort($stats);
        }
    }

    return $stats;
}
