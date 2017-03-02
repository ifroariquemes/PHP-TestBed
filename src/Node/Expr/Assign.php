<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class Assign extends \PhpTestBed\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Expr\Assign $statement)
    {
        parent::__construct($statement);
    }

    private function printScalar($var, $value)
    {
        $this->printMessage(I18n::getInstance()->get('code.assign', [
                    'var' => Stylizer::variable($var),
                    'value' => Stylizer::value($value)
        ]));
    }

    private function printOperation($var, $expr)
    {
        $this->printMessage(I18n::getInstance()->get('code.assign-op', [
                    'var' => Stylizer::variable($var),
                    'value' => $expr
        ]));
    }

    private function printVariable($var, $value, $refVar)
    {
        $left = Stylizer::variable("\${$var}");
        $opSignal = Stylizer::operation("=");
        $right = Stylizer::variable("\${$refVar}");
        $valueSt = Stylizer::type($value);
        $expr = Stylizer::expression("($left $opSignal $right)");
        $bVar = [
            'value' => $valueSt,
            'expr' => $expr,
            'where' => "$right $opSignal $valueSt"
        ];
        $aVar = [
            'var' => $left,
            'value' => I18n::getInstance()->get('code.binary-op-var', $bVar)
        ];
        $this->printMessage(I18n::getInstance()->get('code.assign-op', $aVar));
    }

    protected function resolve()
    {
        if ($this->node->expr instanceof \PhpParser\Node\Scalar) {
            $this->printScalar($this->node->var->name, $this->node->expr->value);
            \PhpTestBed\Repository::getInstance()->set($this->node->var->name, $this->node->expr->value);
        } elseif ($this->node->expr instanceof \PhpParser\Node\Expr\PostInc) {
            $pValue = \PhpTestBed\Repository::getInstance()->get($this->node->expr->var->name);
            $this->printVariable($this->node->var->name, $pValue, $this->node->expr->var->name);
            new PostInc($this->node->expr);
        } elseif ($this->node->expr instanceof \PhpParser\Node\Expr\PreInc) {
            $pInc = new PreInc($this->node->expr);
            $this->printVariable($this->node->var->name, $pInc->getValue(), $this->node->expr->var->name);
        } elseif ($this->node->expr instanceof \PhpParser\Node\Expr\Variable) {
            $currentValue = \PhpTestBed\Repository::getInstance()->get($this->node->expr->name);
            $this->printVariable($this->node->var->name, $currentValue, $this->node->expr->name);
            \PhpTestBed\Repository::getInstance()->set($this->node->var->name, $currentValue);
        } elseif ($this->node->expr instanceof \PhpParser\Node\Expr\BinaryOp) {
            $bOp = new BinaryOp($this->node->expr);
            $this->printOperation($this->node->var->name, $bOp->message());
            \PhpTestBed\Repository::getInstance()
                    ->set($this->node->var->name, $bOp->getResult());
        }
    }

}
