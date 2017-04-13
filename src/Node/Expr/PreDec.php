<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

/**
 * Previous decrementer as in --$a.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class PreDec extends \PhpTestBed\Node\NodeUsableAbstract
{

    /**
     * Initializes object with a PhpParser PreDec statemtent.
     * @param \PhpParser\Node\Expr\PreDec $node The statement
     */
    public function __construct(\PhpParser\Node\Expr\PreDec $node)
    {
        parent::__construct($node);
    }

    /**
     * Returns the expression message.
     * @return string
     */
    public function getExpr(): string
    {
        return '--' . Stylizer::variable("\${$this->node->var->name}");
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
     * Resolves the statement getting the variable value and sets the new
     * value at Repository.
     */
    public function resolve()
    {
        $this->result = \PhpTestBed\Repository::getInstance()->get($this->node->var->name);
        $this->printMessage(I18n::getInstance()->get('code.post-dec', [
                    'var' => Stylizer::variable("\${$this->node->var->name}"),
                    'value' => Stylizer::value(--$this->result)
        ]));
        \PhpTestBed\Repository::getInstance()
                ->set($this->node->var->name, $this->result);
    }

    /**
     * Adds the usage of the variable being decremented
     */
    public function addUsage()
    {
        \PhpTestBed\Repository::getInstance()->addUsedVariable($this->node->var->name);
    }

}
