<?php

namespace PhpTestBed;

use Philasearch\I18n\I18n as Lang;

class I18n
{

    use \FlorianWolters\Component\Util\Singleton\SingletonTrait;

    private $locale;
    private $i18n;

    protected function __construct($locale = 'pt_BR')
    {
        $this->i18n = new Lang(dirname(__FILE__) . '/Lang');
        $this->locale = $locale;
    }

    public static function setLocale($locale)
    {
        self::getInstance($locale);
    }

    public function get($key, $vars = array(), $count = 1)
    {
        $ret = $this->i18n->get($this->locale, "app.$key", $vars, $count);
        return (!empty($ret)) ? $ret : "Index $key not set for current locale ($this->locale)";
    }

}
