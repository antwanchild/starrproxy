<?php

/*
----------------------------------
 ------  Created: 110424   ------
 ------  Austin Best	   ------
----------------------------------
*/

trait Starrs
{
    public function getStarrsTable()
    {
        $starrs = [];

        $q = "SELECT *
              FROM " . STARRS_TABLE . "
              ORDER BY name ASC";
        $r = $this->query($q);
        while ($row = $this->fetchAssoc($r)) {
            $starrs[] = $row;
        }

        return $starrs;
    }

    public function getStarrAppFromId($id, $starrsTable)
    {
        $starrsTable ??= $this->getStarrsTable();

        foreach ($starrsTable as $starrApp) {
            if ($starrApp['id'] == $id) {
                return $starrApp;
            }
        }

        return [];
    }

    public function deleteStarrApp($starrId)
    {
        $q = "DELETE FROM " . STARRS_TABLE . "
              WHERE id = " . intval($starrId);
        $this->query($q);

        if ($this->error() != 'not an error') {
            return $this->error();
        }

        return;
    }

    public function addStarrApp($starrApp, $fields = [])
    {
        $q = "INSERT INTO " . STARRS_TABLE . "
              (`starr`, `name`, `url`, `apikey`, `username`, `password`) 
              VALUES 
              ('". $this->starr->getStarrInterfaceIdFromName($starrApp) ."', '" . $this->prepare($fields['name']) . "', '" . $this->prepare($fields['url']) . "', '" . $this->prepare($fields['apikey']) . "', '" . $this->prepare($fields['username']) . "', '" . $this->prepare($fields['password']) . "')";
        $this->query($q);

        if ($this->error() != 'not an error') {
            return $this->error();
        }

        return;
    }

    public function updateStarrApp($id, $fields = [])
    {
        $q = "UPDATE " . STARRS_TABLE . "
              SET name = '" . $this->prepare($fields['name']) . "', url = '" . $this->prepare($fields['url']) . "'', apikey = '" . $this->prepare($fields['apikey']) . "', username = '" . $this->prepare($fields['username']) . "', password = '" . $this->prepare($fields['password']) . "'
              WHERE id = " . intval($id);
        $this->query($q);

        if ($this->error() != 'not an error') {
            return $this->error();
        }

        return;
    }

    public function updateStarrAppSetting($id, $field, $value)
    {
        $value = $field != 'endpoints' ? $this->prepare($value) : $value;

        $q = "UPDATE " . STARRS_TABLE . "
              SET `" . $field . "` = '" . $value . "'
              WHERE id = " . intval($id);
        $this->query($q);

        if ($this->error() != 'not an error') {
            return $this->error();
        }

        return;
    }
}
