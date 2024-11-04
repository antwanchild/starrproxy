<?php

/*
----------------------------------
 ------  Created: 110424   ------
 ------  Austin Best	   ------
----------------------------------
*/

trait Apps
{
    public function getAppsTable()
    {
        $apps = [];

        $q = "SELECT *
              FROM " . APPS_TABLE . "
              ORDER BY name ASC";
        $r = $this->query($q);
        while ($row = $this->fetchAssoc($r)) {
            $apps[] = $row;
        }

        return $apps;
    }

    public function getAppFromId($appId, $appsTable)
    {
        $appsTable ??= $this->getAppsTable();

        foreach ($appsTable as $app) {
            if ($app['id'] == $appId) {
                return $app;
            }
        }

        return [];
    }

    public function addApp($fields = [])
    {
        $q = "INSERT INTO " . APPS_TABLE . "
              (`name`, `apikey`, `endpoints`, `starr_id`) 
              VALUES 
              ('" . $this->prepare($fields['name']) . "', '" . $this->prepare($fields['apikey']) . "', '" . json_encode($fields['endpoints']) . "', '" . intval($fields['starr_id']) . "')";
        $this->query($q);

        if ($this->error() != 'not an error') {
            return $this->error();
        }

        return;
    }

    public function updateApp($appId, $fields = [])
    {
        $q = "UPDATE " . APPS_TABLE . "
              SET name = '" . $this->prepare($fields['name']) . "', apikey = '" . $this->prepare($fields['apikey']) . "', endpoints = '" . json_encode($fields['endpoints']) . "', starr_id = '" . intval($fields['starr_id']) . "'
              WHERE id = " . intval($appId);
        $this->query($q);

        if ($this->error() != 'not an error') {
            return $this->error();
        }

        return;
    }

    public function deleteApp($appId)
    {
        $q = "DELETE FROM " . APPS_TABLE . "
              WHERE id = " . intval($appId);
        $this->query($q);

        if ($this->error() != 'not an error') {
            return $this->error();
        }

        return;
    }

    public function adjustAppUsage($appId, $code)
    {
        $field = str_equals_any($code, [401, 405]) ? 'rejected' : 'allowed';

        $q = "UPDATE " . USAGE_TABLE . "
              SET " . $field . " = " . $field . " + 1
              WHERE app_id = " . $appId;
        $this->query($q);

        if ($this->error() != 'not an error' || $this->affectedRows() == 0) {
            $q = "INSERT INTO " . USAGE_TABLE . "
                  ('app_id', 'allowed', 'rejected') 
                  VALUES 
                  ('" . $appId . "', " . ($field == 'allowed' ? 1 : 0) . ", " . ($field == 'rejected' ? 1 : 0) . ")";
            $this->query($q);
        }
    }

    public function getAppUsage($appId)
    {
        $q = "SELECT *
              FROM " . USAGE_TABLE . "
              WHERE app_id = " . intval($appId);
        $r = $this->query($q);
        $row = $this->fetchAssoc($r);

        return $row ?: [];
    }

    public function resetAppUsage($appId)
    {
        $q = "UPDATE " . USAGE_TABLE . "
              SET allowed = 0, rejected = 0 
              WHERE app_id = " . intval($appId);
        $this->query($q);

        if ($this->error() != 'not an error') {
            return $this->error();
        }

        return;
    }
}
