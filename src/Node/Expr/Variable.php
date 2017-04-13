<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\Stylizer;
use PhpTestBed\I18n;

/**
 * Represents a variable.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.1.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Variable extends \PhpTestBed\Node\NodeUsableAbstract
{

    /**
     * Initializes object with a PhpParser Variable statemtent.
     * @param \PhpParser\Node\Expr\Variable $node The statement
     */
    public function __construct(\PhpParser\Node\Expr\Variable $node)
    {
        parent::__construct($node);
    }

    /**
     * Resolves the statement getting the variable value at Repository.
     */
    public function resolve()
    {
        $this->result = \PhpTestBed\Repository::getInstance()->get($this->node->name);
    }

    /**
     * Returns the expression message.
     * @return string
     */
    public function getExpr(): string
    {
        return Stylizer::variable("\${$this->node->name}");
    }

    /**
     * Return the output message.
     * @return string
     */
    public function getMessage(): string
    {
        return I18n::getInstance()->get('code.binary-op-var', [
                    'expr' => Stylizer::expression($this->getExpr()),
                    'value' => !is_array($this->result) ?
                    Stylizer::value($this->result) :
                    Array_::prepareArrayToPrint($this->result),
                    'where' => \PhpTestBed\Repository::getInstance()->showUsed()
        ]);
    }

    /**
     * Adds the usage of the variable being used
     */
    public function addUsage()
    {
        \PhpTestBed\Repository::getInstance()->addUsedVariable($this->node->name, $this->result);
    }

}
