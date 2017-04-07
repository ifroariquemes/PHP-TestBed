<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;

/**
 * Throw statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Throw_ extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * Initializes object with a PhpParser Throw_ statemtent.
     * @param \PhpParser\Node\Stmt\Throw_ $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\Throw_ $node)
    {
        parent::__construct($node);
    }

    /**
     * Prints the starter message.
     */
    protected function printEnterMessage()
    {
        $this->printMessage(I18n::getInstance()->get('code.throw'));
    }

    /**
     * Resolves the throw statement calling throw state at ScriptCrawler.
     * If it is not inside a try-catch statement, script execution
     * will abort.
     */
    public function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        if (!$scriptCrawler->callThrow($this->node)) {
            $scriptCrawler->callExit();
        }
    }

}
