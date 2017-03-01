<?php

namespace PhpTestBed\Node\Scalar;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class Encapsed extends \PhpTestBed\ResolverAbstract
{

    private $result = '';
    private $expr = '';
    private $usedVars = array();

    public function __construct(\PhpParser\Node\Scalar\Encapsed $node)
    {
        parent::__construct($node);
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getExpr()
    {
        return Stylizer::value($this->expr);
    }

    public function getUsedVars()
    {
        return $this->usedVars;
    }

    protected function resolve()
    {
        foreach ($this->node->parts as $expr) {
            switch (get_class($expr)) {
                case \PhpParser\Node\Scalar\EncapsedStringPart::class:
                    $this->result .= $expr->value;
                    $this->expr .= $expr->value;
                    break;
                case \PhpParser\Node\Expr\Variable::class:
                    $value = \PhpTestBed\Repository::getInstance()->get($expr->name);
                    $this->result .= $value;
                    $this->expr .= Stylizer::variable("\${$expr->name}");
                    $this->usedVars[$expr->name] = $value;
                    break;
            }
        }
    }

}
