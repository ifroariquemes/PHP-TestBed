<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

/**
 * Global statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Global_ extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * Initializes object with a PhpParser Global_ statemtent.
     * @param \PhpParser\Node\Stmt\Global_ $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\Global_ $node)
    {
        parent::__construct($node);
    }

    /**
     * Prints a message for the global variable initialization.
     * @param string $varName
     */
    private function printGlobalMessage(string $varName)
    {
        $this->printMessage(I18n::getInstance()->get('code.global', [
                    'var' => Stylizer::variable($varName)
        ]));
    }

    /**
     * Resolves the global statement just generating a message of initialization.
     */
    public function resolve()
    {
        foreach ($this->node->vars as $var) {
            $this->printGlobalMessage($var->name);
        }
    }

}
