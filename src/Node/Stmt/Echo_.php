<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class Echo_ extends \PhpTestBed\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\Echo_ $node)
    {
        parent::__construct($node);
    }

    private function printScalar($value)
    {
        $mVar = [
            'value' => Stylizer::value($value)
        ];
        $this->printMessage(I18n::getInstance()->get('code.echo-scalar', $mVar));
    }

    private function printOperation($value, $expr, $usedVars)
    {
        $eVar = [
            'value' => Stylizer::value($value),
            'expr' => Stylizer::expression($expr),
            'where' => \PhpTestBed\Repository::showUsed([], $usedVars)
        ];
        $mVar = [
            'value' => I18n::getInstance()->get('code.binary-op-var', $eVar)
        ];
        $this->printMessage(I18n::getInstance()->get('code.echo-scalar', $mVar));
    }

    private function printVariable($var, $value)
    {
        $varName = Stylizer::variable("\${$var}");
        $eVar = [
            'value' => Stylizer::value($value),
            'expr' => Stylizer::expression("($varName)"),
            'where' => \PhpTestBed\Repository::showUsed([], [$var => $value])
        ];
        $mVar = [
            'value' => I18n::getInstance()->get('code.binary-op-var', $eVar)
        ];
        $this->printMessage(I18n::getInstance()->get('code.echo-scalar', $mVar));
    }

    protected function resolve()
    {
        foreach ($this->node->exprs as $expr) {
            if ($expr instanceof \PhpParser\Node\Scalar\Encapsed) {
                $enc = new \PhpTestBed\Node\Scalar\Encapsed($expr);
                $this->printOperation($enc->getResult(), $enc->getExpr(), $enc->getUsedVars());
            } elseif ($expr instanceof \PhpParser\Node\Scalar) {
                $this->printScalar($expr->value);
            } elseif ($expr instanceof \PhpParser\Node\Expr\Variable) {
                $value = \PhpTestBed\Repository::getInstance()->get($expr->name);
                $this->printVariable($expr->name, $value);
            } elseif ($expr instanceof \PhpParser\Node\Expr\PostInc) {
                $pValue = \PhpTestBed\Repository::getInstance()->get($expr->var->name);
                $varName = Stylizer::variable("\${$expr->var->name}");
                $this->printOperation($pValue, "($varName)", [$expr->var->name => $pValue]);
            } elseif ($expr instanceof \PhpParser\Node\Expr\PreInc) {
                $pInc = new \PhpTestBed\Node\Expr\PreInc($expr);
                $varName = Stylizer::variable("\${$expr->var->name}");
                $this->printOperation($pInc->getValue(), "($varName)", [$expr->var->name => $pInc->getValue()]);
            } else {
                $line = new \PhpTestBed\Node\Expr\BinaryOp($expr);
                $this->printMessage(sprintf('%s %s', I18n::getInstance()->get('code.echo'), $line->message()));
            }
        }
    }

}
