<?php

namespace PhpTestBed\Node\Stmt;

/**
 * Break statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Break_ extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * Initializes object with a PhpParser Break_ statemtent.
     * @param \PhpParser\Node\Stmt\Break_ $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\Break_ $node)
    {
        parent::__construct($node);
    }

    /**
     * Prints the starter message.
     */
    protected function printEnterMessage()
    {
        parent::__printEnterMessage('code.break');
    }

    /**
     * Resolves the break statement calling break state from ScriptCrawler.
     */
    public function resolve()
    {
        \PhpTestBed\ScriptCrawler::getInstance()->callBreak();
    }

}
