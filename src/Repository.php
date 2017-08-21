<?php

namespace PhpTestBed;

use PhpTestBed\Stylizer;

/**
 * The Repository stores all initilized variables, constants and arrays during
 * the script execution. It also keeps record of what resources were used
 * for each single statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @since Release 0.1.0
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Repository
{

    use \FlorianWolters\Component\Util\Singleton\SingletonTrait;

    /**
     * Stores the actual state of variables during script execution.
     * @var array
     */
    private $variables;

    /**
     * Stores the actual state of constants during script execution.
     * @var array
     */
    private $constants;

    /**
     * Used variables during current statement execution.
     * @var array
     */
    private $usedVariables;

    /**
     * Used contants during current statement execution.
     * @var array
     */
    private $usedConstants;

    /**
     * Used arrays items during current statement execution.
     * @var array
     */
    private $usedArrays;

    /**
     * Initilizes an empty repository.
     */
    public function __construct()
    {
        $this->variables = array();
        $this->constants = array();
        $this->cleanUsed();
    }

    /**
     * Returns the current variable value.
     * @param string $varName The variable name
     * @return mixed
     */
    public function get(string $varName)
    {
        if (!array_key_exists($varName, $this->variables)) {
            return null;
        }
        return $this->variables[$varName];
    }

    /**
     * Sets a given value to a variable into repository.
     * @param string $varName The variable name
     * @param mixed $value The variable value
     */
    public function set(string $varName, $value, $dims = null)
    {
        if (empty($dims)) {
            $this->variables[$varName] = $value;
        } else {
            $aux = &$this->variables[$varName];
            foreach ($dims as $dim) {
                $dim = strip_tags($dim);
                $aux = &$aux[ctype_digit($dim) ? intval($dim) : $dim];
            }
            $aux = $value;
            unset($aux);
        }
    }

    /**
     * Returns the variables repository.
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * Returns the constant value.
     * @param string $constName The constant name
     * @return mixed
     */
    public function getConst(string $constName)
    {
        if (!array_key_exists($constName, $this->constants)) {
            return null;
        }
        return $this->constants[$constName];
    }

    /**
     * Sets a given value to a constant into repository.
     * @param string $constName The constant name
     * @param mixed $value The constant value
     */
    public function setConst(string $constName, $value)
    {
        $this->constants[$constName] = $value;
    }

    /**
     * Clear used resources arrays. To be executed at the end of each statement.
     */
    public function cleanUsed()
    {
        $this->usedArrays = array();
        $this->usedConstants = array();
        $this->usedVariables = array();
    }

    /**
     * Includes the information of using a variable during current statement execution.
     * @param string $varName The variable name
     * @param mixed $value The variable value. If null, then will get it from repository itself
     */
    public function addUsedVariable(string $varName, $value = null)
    {
        $this->usedVariables[$varName] = $value ?? $this->get($varName);
    }

    /**
     * Includes the information of using a constant during current statement execution.
     * @param string $constName The constant name
     * @param mixed $value The constant value. If null, then will get it from repository itself
     */
    public function addUsedConstant(string $constName, $value = null)
    {
        $this->usedConstants[$constName] = $value ?? $this->getConst($constName);
    }

    /**
     * Includes the information of using a array item during current statement execution.
     * @param string $varName The variable name where array is stored
     * @param array $keys Array with indexes used to access the desired item
     * @param mixed $value The array item value. If null, then will get it from repository itself
     */
    public function addUsedArray(string $varName, array $keys, $value = null)
    {
        $theValue = $value;
        if (is_null($value)) {
            $theValue = $this->get($varName);
            foreach ($keys as $key) {
                $theValue = $theValue[$key];
            }
        }
        $newArray = ['var' => $varName, 'key' => $keys, 'value' => $theValue];
        if (!in_array($newArray, $this->usedArrays)) {
            array_push($this->usedArrays, $newArray);
        }
    }

    /**
     * Returns the message for used constants.
     * @return string
     */
    private function getUsedConstantsMessage(): string
    {
        $usedConstants = '';
        if (!empty($this->usedConstants)) {
            foreach ($this->usedConstants as $const => $value) {
                $usedConstants .= sprintf('%s %s %s, '
                        , Stylizer::constant($const)
                        , Stylizer::operation('=')
                        , Stylizer::type($value));
            }
        }
        return $usedConstants;
    }

    /**
     * Returns the message for used variables.
     * @return string
     */
    private function getUsedVariablesMessage(): string
    {
        $usedVariables = '';
        if (!empty($this->usedVariables)) {
            foreach ($this->usedVariables as $var => $value) {
                $usedVariables .= sprintf('%s %s %s, '
                        , Stylizer::variable("$$var")
                        , Stylizer::operation('=')
                        , Stylizer::type($value));
            }
        }
        return $usedVariables;
    }

    private function getUsedArraysMessage(): string
    {
        $usedArrays = '';
        if (!empty($this->usedArrays)) {
            foreach ($this->usedArrays as $arr) {
                $key = $this->getUsedKeysMessage($arr['key']);
                $usedArrays .= sprintf('%s %s %s, '
                        , Stylizer::variable("\${$arr['var']}") . $key
                        , Stylizer::operation('=')
                        , Stylizer::type($arr['value']));
            }
        }
        return $usedArrays;
    }

    /**
     * Returns the message for used keys within arrays.
     * @param array $keys The given keys
     * @return string
     */
    private function getUsedKeysMessage(array $keys): string
    {
        $usedKeys = '';
        foreach ($keys as $key) {
            $usedKeys .= sprintf('[%s]', Stylizer::type($key));
        }
        return $usedKeys;
    }

    /**
     * Returns the complete message for used resources from repository.
     * @return string
     */
    public function showUsed(): string
    {
        $used = $this->getUsedConstantsMessage() .
                $this->getUsedVariablesMessage() .
                $this->getUsedArraysMessage();
        if (!empty($used)) {
            $used = substr($used, 0, -2);
        }
        $this->cleanUsed();
        return $used;
    }

}
