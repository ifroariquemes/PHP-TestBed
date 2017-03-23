<?php

namespace PhpTestBed;

class Stylizer
{

    public static function expression($message)
    {
        $code = '<span class="testbed-expression">%s</span>';
        return sprintf($code, $message);
    }

    public static function operation($message)
    {
        $code = '<span class="testbed-operation">%s</span>';
        return sprintf($code, $message);
    }

    public static function type($message)
    {
        $code = '<span class="testbed-type-%s">%s</span>';
        $type = gettype($message);
        if (is_string($message)) {
            $message = "'$message'";
        } elseif (is_bool($message)) {
            $message = var_export($message, true);
        }
        return sprintf($code, $type, $message ?? 'null');
    }

    public static function variable($message)
    {
        $code = '<span class="testbed-variable">%s</span>';
        $var = strpos($message, '$') === 0 ? $message : "$$message";
        return sprintf($code, $var);
    }

    public static function value($message)
    {
        $code = '<span class="testbed-value">%s</span>';
        return sprintf($code, self::type($message));
    }

    public static function systemException($message)
    {
        $code = '<span class="testbed-system-exception">%s</span>';
        return sprintf($code, $message);
    }

    public static function systemMessage($message)
    {
        $code = '<span class="testbed-system-message">%s</span>';
        return sprintf($code, $message);
    }

}
