<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class PreDec extends \PhpTestBed\Node\NodeUsableAbstract
{

    public function __construct(\PhpParser\Node\Expr\PreDec $node)
    {
        parent::__construct($node);
    }

    public function getExpr()
    {
        return '--' . Stylizer::variable("\${$this->node->var->name}");
    }

    public function getMessage()
    {
        return I18n::getInstance()->get('code.binary-op-var', [
                    'value' => Stylizer::type($this->getResult()),
                    'expr' => Stylizer::expression($this->getExpr()),
                    'where' => \PhpTestBed\Repository::getInstance()->showUsed()
        ]);
    }

    public function resolve()
    {
        $this->result = \PhpTestBed\Repository::getInstance()->get($this->node->var->name);
        $mVar = [
            'var' => Stylizer::variable("\${$this->node->var->name}"),
            'value' => Stylizer::value(--$this->result)
        ];
        $this->printMessage(I18n::getInstance()->get('code.post-dec', $mVar));
        \PhpTestBed\Repository::getInstance()
                ->set($this->node->var->name, $this->result);
    }

    public function addUsage()
    {
        \PhpTestBed\Repository::getInstance()->addUsedVariable($this->node->var->name);
    }

}
