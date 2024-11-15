<?php

/*
----------------------------------
 ------  Created: 101124   ------
 ------  Austin Best	   ------
----------------------------------
*/

function getTemplateOptions()
{
    $templateList = getTemplateList();

    $templateOptions = '';
    foreach ($templateList as $app => $appTemplates) {
        $templateOptions .= '<optgroup label="' . $app . '">';

        foreach ($appTemplates as $appTemplate) {
            $custom = str_contains($appTemplate['location'], APP_USER_TEMPLATES_PATH);
            $templateOptions .= '<option value="' . $appTemplate['location'] . $appTemplate['starr'] . '/' . $app . '.json">' . $appTemplate['starr'] . ($custom ? ' [Custom]' : '') . '</option>';
        }
        $templateOptions .= '</optgroup>';
    }

    return '<option value="0">-- Select a template --</option>' . $templateOptions;
}

function getTemplateList()
{
    $templateLocations = [ABSOLUTE_PATH . 'templates/', APP_USER_TEMPLATES_PATH];
    $list = [];
    foreach (StarrApps::LIST as $starrApp) {
        foreach ($templateLocations as $templateLocation) {
            if (is_dir($templateLocation . $starrApp)) {
                $dir = opendir($templateLocation . $starrApp);
                while ($template = readdir($dir)) {
                    if (!str_contains($template, '.json')) {
                        continue;
                    }
            
                    $template = str_replace('.json', '', $template);
                    $list[$template][] = ['location' => $templateLocation, 'starr' => $starrApp];
                }
                closedir($dir);
                ksort($list);
            }
        }
    }

    return $list;
}
