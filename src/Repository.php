<?php

namespace PhpTestBed;

class Repository
{

    use \FlorianWolters\Component\Util\Singleton\SingletonTrait;

    private $repository = array();

    public function get($key)
    {
        if (!array_key_exists($key, $this->repository)) {
            //trigger_error("Key for \$$key does not exists at variable repository.", E_USER_NOTICE);
            return null;
        }
        return $this->repository[$key];
    }

    public function set($key, $value)
    {
        $this->repository[$key] = $value;
    }

    public function getRepository()
    {
        return $this->repository;
    }

}
