<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\Stylizer;
use PhpTestBed\I18n;

class Variable extends \PhpTestBed\Node\ResolverAbstract
{

    private $value;

    public function __construct(\PhpParser\Node\Expr\Variable $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        $this->value = \PhpTestBed\Repository::getInstance()->get($this->node->name);
    }

    public function getExpr()
    {
        return Stylizer::variable("\${$this->node->name}");
    }

    public function message()
    {
        $mVar = [
            'expr' => Stylizer::expression($this->getExpr()),
            'value' => Stylizer::value($this->value),
            'where' => \PhpTestBed\Repository::showUsed([], [$this->node->name => $this->value], [])
        ];
        return I18n::getInstance()->get('code.binary-op-var', $mVar);
    }

    public function getResult()
    {
        return $this->value;
    }

}
