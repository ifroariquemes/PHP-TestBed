<?php

namespace PhpTestBed;

/**
 * A class that implements method for use when printing script parts.
 * It involves a single piece into HTML tags with CSS classes that
 * gives meaning to its content. They are used in the future to color and 
 * organize message printed on screen.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @since Release 0.1.0
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Stylizer
{

    use \FlorianWolters\Component\Util\Singleton\SingletonTrait;

    /**
     * Returns the message inside a structure that means expression
     * @param string $message The expression
     * @return string
     */
    public static function expression(string $message): string
    {
        $code = '<span class="testbed-expression">%s</span>';
        return sprintf($code, $message);
    }

    /**
     * Returns the message inside a structure that means operation
     * @param string $message The operation
     * @return string
     */
    public static function operation(string $message): string
    {
        $code = '<span class="testbed-operation">%s</span>';
        return sprintf($code, $message);
    }

    /**
     * Returns the value inside a structure that means data type
     * @param mixed $message The value
     * @return string
     */
    public static function type($message): string
    {
        $code = '<span class="testbed-type-%s">%s</span>';
        $type = gettype($message);
        switch ($type) {
            case 'string':
                $message = "'$message'";
                break;
            case 'boolean':
                $message = var_export($message, true);
                break;
            case 'array':
                $message = 'array';
                break;
        }
        return sprintf($code, $type, $message ?? 'null');
    }

    /**
     * Returns the variable name inside a structure that means variable
     * @param string $message The variable name
     * @return string
     */
    public static function variable(string $message): string
    {
        $code = '<span class="testbed-variable">%s</span>';
        $var = strpos($message, '$') === 0 ? $message : "$$message";
        return sprintf($code, $var);
    }

    /**
     * Returns the message inside a structure that means constant
     * @param string $message The constant name
     * @return string
     */
    public static function constant(string $message): string
    {
        $code = '<span class="testbed-constant">%s</span>';
        return sprintf($code, $message);
    }

    /**
     * Returns the message inside a structure that means value
     * @param mixed $message The value
     * @return string
     */
    public static function value($message): string
    {
        $code = '<span class="testbed-value">%s</span>';
        return sprintf($code, self::type($message));
    }

    /**
     * Returns the message inside a structure that means system exception
     * @param string $message The message
     * @return string
     */
    public static function systemException(string $message): string
    {
        $code = '<span class="testbed-system-exception">%s</span>';
        return sprintf($code, $message);
    }

    /**
     * Returns the message inside a structure that means system message
     * @param string $message The message
     * @return string
     */
    public static function systemMessage(string $message): string
    {
        $code = '<span class="testbed-system-message">%s</span>';
        return sprintf($code, $message);
    }

    /**
     * Returns the message inside a structure that class name
     * @param string $message The class name
     * @return string
     */
    public static function classRef(string $message): string
    {
        $code = '<span class="testbed-class">%s</span>';
        return sprintf($code, $message);
    }

}
