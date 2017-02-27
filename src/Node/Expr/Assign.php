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

    protected function resolve()
    {
        if ($this->node->expr instanceof \PhpParser\Node\Scalar) {
            $mVar = [
                'var' => Stylizer::variable("\${$this->node->var->name}"),
                'value' => Stylizer::value($this->node->expr->value)
            ];
            $this->printMessage(I18n::getInstance()->get('code.assign', $mVar));
            \PhpTestBed\Repository::getInstance()
                    ->set($this->node->var->name
                            , $this->node->expr->value);
        } elseif ($this->node->expr instanceof \PhpParser\Node\Expr\Variable) {
            $currentValue = \PhpTestBed\Repository::getInstance()->get($this->node->expr->name);
            $left = Stylizer::variable("\${$this->node->var->name}");
            $opSignal = Stylizer::operation("=");
            $right = Stylizer::variable("\${$this->node->expr->name}");
            $value = Stylizer::type($currentValue);
            $expr = Stylizer::expression("($left $opSignal $right)");
            $bVar = [
                'value' => $value,
                'expr' => $expr,
                'where' => "$right $opSignal $value"
            ];
            $aVar = [
                'var' => $left,
                'value' => I18n::getInstance()->get('code.binary-op-var', $bVar)
            ];
            $this->printMessage(I18n::getInstance()->get('code.assign-op', $aVar));
            \PhpTestBed\Repository::getInstance()->set($this->node->var->name, $currentValue);
        } elseif ($this->node->expr instanceof \PhpParser\Node\Expr\BinaryOp) {
            $bOp = new BinaryOp($this->node->expr);
            $mVar = [
                'var' => Stylizer::variable("\${$this->node->var->name}"),
                'value' => $bOp->message()
            ];
            $this->printMessage(I18n::getInstance()->get('code.assign-op', $mVar));
            \PhpTestBed\Repository::getInstance()
                    ->set($this->node->var->name, $bOp->getResult());
        }
    }

}
