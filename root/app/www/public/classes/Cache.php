<?php

/*
----------------------------------
 ------  Created: 111724   ------
 ------  Austin Best	   ------
----------------------------------
*/

//-- BRING IN THE EXTRAS
loadClassExtras('Cache');

class Cache
{
    public $cache;

    public function __construct()
    {
        $this->init();
    }

    public function __tostring()
    {
        return 'Class loaded: Cache';
    }

    public function init()
    {
        $this->cache = new Memcached();
        $this->cache->addServer('127.0.0.1', '11211') or die('Cache connection failure');
    }

    public function set($key, $data, $seconds)
    {
        if (!$this->cache) {
            $this->init();
        }

        if ($key && $data && $seconds) {
            $this->cache->set($key, $data, $seconds);
        }
    }

    public function get($key)
    {
        if (!$this->cache) {
            $this->init();
        }

        if (!$key) {
            return;
        }

        return $this->cache->get($key);
    }

    public function bust($key)
    {
        if (!$this->cache) {
            $this->init();
        }

        if (!$key) {
            return;
        }

        $this->cache->delete($key);
    }
}
