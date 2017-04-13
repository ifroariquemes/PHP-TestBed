<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

/**
 * Constant statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Const_ extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * Initializes object with a PhpParser Const_ statemtent.
     * @param \PhpParser\Node\Stmt\Const_ $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\Const_ $node)
    {
        parent::__construct($node);
    }

    /**
     * Prints the constant information.
     * @param string $constName The constant name
     * @param mixed $value The constant value
     */
    private function printConst(string $constName, $value)
    {
        $this->printMessage(I18n::getInstance()->get('code.const', [
                    'const' => Stylizer::constant($constName),
                    'value' => Stylizer::value($value)
        ]));
    }

    /**
     * Resolves the constant statement getting the constant value and
     * then creates a repository entry for that constant.
     */
    public function resolve()
    {
        foreach ($this->node->consts as $expr) {
            $value = \PhpTestBed\Node\NodeLoader::load($expr->value);
            $this->printConst($expr->name, $value->getResult());
            \PhpTestBed\Repository::getInstance()->setConst($expr->name, $value->getResult());
        }
    }

}
