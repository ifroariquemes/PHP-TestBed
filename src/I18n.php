<?php

namespace PhpTestBed;

use Philasearch\I18n\I18n as Lang;

/**
 * Internationalization class to implement support for multiple languages.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.1.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class I18n
{

    use \FlorianWolters\Component\Util\Singleton\SingletonTrait;

    /**
     * The locale to load the apropriate language file from src/Lang.
     * @var string
     */
    private $locale;

    /**
     *
     * @var \Philasearch\I18n\I18n
     */
    private $i18n;

    /**
     * Initializes this class object. If no locale is set at begining then
     * pt_BR will be used as default.
     * @param string $locale The language pack, with folder inside src/Lang
     */
    protected function __construct(string $locale = 'pt_BR')
    {
        $this->i18n = new Lang(dirname(__FILE__) . '/Lang');
        $this->locale = $locale;
    }

    /**
     * Sets a locale.
     * @param string $locale The language pack, with folder inside src/Lang
     */
    public static function setLocale(string $locale)
    {
        self::getInstance($locale);
    }

    /**
     * Returns the message at locale file under given key.
     * @param string $key The message key/index inside locale file
     * @param array $vars Array with message variables parts where its keys are replaced in text by its value (e.g. :name)
     * @param int $count Number of objects to use that message plural form
     * @return string
     * @see \Philasearch\I18n\I18n::get
     */
    public function get(string $key, array $vars = array(), int $count = 1)
    {
        $errLevel = error_reporting(E_NOTICE);
        $ret = $this->i18n->get($this->locale, "app.$key", $vars, $count);
        error_reporting($errLevel);
        return (!empty($ret)) ? $ret : "Index $key not set for current locale ($this->locale)";
    }

}
