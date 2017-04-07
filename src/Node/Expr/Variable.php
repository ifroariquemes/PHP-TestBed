<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\Stylizer;
use PhpTestBed\I18n;

class Variable extends \PhpTestBed\Node\NodeUsableAbstract
{

    public function __construct(\PhpParser\Node\Expr\Variable $node)
    {
        parent::__construct($node);
    }

    public function resolve()
    {
        $this->result = \PhpTestBed\Repository::getInstance()->get($this->node->name);
    }

    public function getExpr()
    {
        return Stylizer::variable("\${$this->node->name}");
    }

    public function getMessage()
    {
        $mVar = [
            'expr' => Stylizer::expression($this->getExpr()),
            'value' => !is_array($this->result) ?
            Stylizer::value($this->result) :
            Array_::prepareArrayToPrint($this->result),
            'where' => \PhpTestBed\Repository::getInstance()->showUsed()
        ];
        return I18n::getInstance()->get('code.binary-op-var', $mVar);
    }

    public function addUsage()
    {
        \PhpTestBed\Repository::getInstance()->addUsedVariable($this->node->name, $this->result);
    }

}
