<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;

/**
 * Echo statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.1.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Echo_ extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * Initializes object with a PhpParser Echo_ statemtent.
     * @param \PhpParser\Node\Stmt\Echo_ $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\Echo_ $node)
    {
        parent::__construct($node);
    }

    /**
     * Resolves the echo statement printing the message of all 
     * nested expressions.
     */
    public function resolve()
    {
        foreach ($this->node->exprs as $expr) {
            $nodeExpr = \PhpTestBed\Node\NodeLoader::load($expr);
            if (!is_null($nodeExpr)) {
                $this->printMessage(I18n::getInstance()->get('code.echo') . ' '
                        . $nodeExpr->getMessage());
            }
        }
    }

}
