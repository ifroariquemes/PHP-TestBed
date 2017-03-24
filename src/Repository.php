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

    public static function showUsed(array $consts, array $vars, array $arrays = null)
    {
        $used = '';
        foreach ($consts as $const => $value) {
            $used .= sprintf('%s %s %s, '
                    , Stylizer::constant($const)
                    , Stylizer::operation('=')
                    , Stylizer::type($value));
        }
        foreach ($vars as $var => $value) {
            $used .= sprintf('%s %s %s, '
                    , Stylizer::variable("$$var")
                    , Stylizer::operation('=')
                    , Stylizer::type($value));
        }
        if (!is_null($arrays)) {
            foreach ($arrays as $arr) {
                if (is_array($arr['key'])) {
                    $key = '';
                    foreach ($arr['key'] as $arrKey) {
                        $key .= sprintf('[%s]', Stylizer::type($arrKey));
                    }
                } else {
                    $key = sprintf('[%s]', Stylizer::type($arr['key']));
                }
                $used .= sprintf('%s %s %s, '
                        , Stylizer::variable("\${$arr['var']}") . $key
                        , Stylizer::operation('=')
                        , Stylizer::type($arr['value']));
            }
        }
        if (!empty($used)) {
            $used = substr($used, 0, -2);
        }
        return $used;
    }

}
