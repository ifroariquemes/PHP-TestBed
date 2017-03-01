<?php

namespace PhpTestBed;

use PhpTestBed\Stylizer;

class Repository
{

    use \FlorianWolters\Component\Util\Singleton\SingletonTrait;

    private $variables = array();
    private $constants = array();

    public function get($key)
    {
        if (!array_key_exists($key, $this->variables)) {
            return null;
        }
        return $this->variables[$key];
    }

    public function set($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function getVariables()
    {
        return $this->variables;
    }

    public function getConst($key)
    {
        if (!array_key_exists($key, $this->constants)) {
            return null;
        }
        return $this->constants[$key];
    }

    public function setConst($key, $value)
    {
        $this->constants[$key] = $value;
    }

    public static function showUsed(array $consts, array $vars)
    {
        $used = '';
        foreach ($consts as $const => $value) {
            $used .= sprintf('%s %s %s, '
                    , Stylizer::variable($const)
                    , Stylizer::operation('=')
                    , Stylizer::type($value));
        }
        foreach ($vars as $var => $value) {
            $used .= sprintf('%s %s %s, '
                    , Stylizer::variable("$$var")
                    , Stylizer::operation('=')
                    , Stylizer::type($value));
        }
        if (!empty($used)) {
            $used = substr($used, 0, -2);
        }
        return $used;
    }

}
