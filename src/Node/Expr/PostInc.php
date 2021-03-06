<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

/**
 * Posterior incrementer as in $a++.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.1.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class PostInc extends \PhpTestBed\Node\NodeUsableAbstract
{

    /**
     * Initializes object with a PhpParser PostInc statemtent.
     * @param \PhpParser\Node\Expr\PostInc $node The statement
     */
    public function __construct(\PhpParser\Node\Expr\PostInc $node)
    {
        parent::__construct($node);
    }

    /**
     * Returns the expression message.
     * @return string
     */
    public function getExpr(): string
    {
        return Stylizer::variable("\${$this->node->var->name}") . '++';
    }

    /**
     * Returns the output message.
     * @return string
     */
    public function getMessage(): string
    {
        return I18n::getInstance()->get('code.binary-op-var', [
                    'value' => Stylizer::type($this->getResult()),
                    'expr' => Stylizer::expression($this->getExpr()),
                    'where' => \PhpTestBed\Repository::getInstance()->showUsed()
        ]);
    }

    /**
     * Prints a message when this class object is destroyed and set the new
     * value at Repository.
     */
    protected function printExitMessage()
    {
        $this->printMessage(I18n::getInstance()->get('code.post-inc', [
                    'var' => Stylizer::variable("\${$this->node->var->name}"),
                    'value' => Stylizer::value( ++$this->result)
        ]));
        \PhpTestBed\Repository::getInstance()
                ->set($this->node->var->name, $this->result);
    }

    /**
     * Resolves the statement getting the variable value.
     */
    public function resolve()
    {
        $this->result = \PhpTestBed\Repository::getInstance()->get($this->node->var->name);
    }

    /**
     * Adds the usage of the variable being incremented
     */
    public function addUsage()
    {
        \PhpTestBed\Repository::getInstance()->addUsedVariable($this->node->var->name);
    }

}
