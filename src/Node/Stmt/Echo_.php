<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class Echo_ extends \PhpTestBed\Node\ResolverAbstract
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
            'expr' => Stylizer::expression("($expr)"),
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
            'where' => \PhpTestBed\Repository::showUsed([], [$var => $value], [])
        ];
        $mVar = [
            'value' => I18n::getInstance()->get('code.binary-op-var', $eVar)
        ];
        $this->printMessage(I18n::getInstance()->get('code.echo-scalar', $mVar));
    }

    private function printArrayDim($var, $keys, $value)
    {
        $keyStyle = '';
        foreach($keys as $key) {
            $keyStyle .= sprintf('[%s]', Stylizer::type($key));
        }
        $varName = Stylizer::variable("\${$var}") . $keyStyle;
        $eVar = [
            'value' => Stylizer::value($value),
            'expr' => Stylizer::expression("($varName)"),
            'where' => \PhpTestBed\Repository::showUsed([], [], [
                ['var' => $var, 'key' => $keys, 'value' => $value]
            ])
        ];
        $mVar = [
            'value' => I18n::getInstance()->get('code.binary-op-var', $eVar)
        ];
        $this->printMessage(I18n::getInstance()->get('code.echo-scalar', $mVar));
    }

    private function printEcho(\PhpParser\Node\Expr\BinaryOp $expr)
    {
        $line = new \PhpTestBed\Node\Expr\BinaryOp($expr);
        $this->printMessage(sprintf('%s %s', I18n::getInstance()->get('code.echo'), $line->message()));
    }

    protected function resolve()
    {
        foreach ($this->node->exprs as $expr) {
            if ($expr instanceof \PhpParser\Node\Expr\Assign) {
                $asgn = new \PhpTestBed\Node\Expr\Assign($expr);
                $this->printVariable($asgn->getVarName(), $asgn->getValue());
            } elseif ($expr instanceof \PhpParser\Node\Scalar\Encapsed) {
                $enc = new \PhpTestBed\Node\Scalar\Encapsed($expr);
                $this->printOperation($enc->getResult(), $enc->getExpr(), $enc->getUsedVars());
            } elseif ($expr instanceof \PhpParser\Node\Scalar) {
                $this->printScalar($expr->value);
            } elseif ($expr instanceof \PhpParser\Node\Expr\Variable) {
                $item = \PhpTestBed\Repository::getInstance()->get($expr->name);
                $this->printVariable($expr->name, $item);
            } elseif ($expr instanceof \PhpParser\Node\Expr\PostInc) {
                $pValue = \PhpTestBed\Repository::getInstance()->get($expr->var->name);
                $varName = Stylizer::variable($expr->var->name);
                $this->printOperation($pValue, "($varName)", [$expr->var->name => $pValue]);
                new \PhpTestBed\Node\Expr\PostInc($expr);
            } elseif ($expr instanceof \PhpParser\Node\Expr\PreInc) {
                $pInc = new \PhpTestBed\Node\Expr\PreInc($expr);
                $varName = Stylizer::variable($expr->var->name);
                $this->printOperation($pInc->getValue(), $varName, [$expr->var->name => $pInc->getValue()]);
            } elseif ($expr instanceof \PhpParser\Node\Expr\PostDec) {
                $pValue = \PhpTestBed\Repository::getInstance()->get($expr->var->name);
                $varName = Stylizer::variable($expr->var->name);
                $this->printOperation($pValue, $varName, [$expr->var->name => $pValue]);
                new \PhpTestBed\Node\Expr\PostDec($expr);
            } elseif ($expr instanceof \PhpParser\Node\Expr\PreDec) {
                $pDec = new \PhpTestBed\Node\Expr\PreDec($expr);
                $varName = Stylizer::variable($expr->var->name);
                $this->printOperation($pDec->getValue(), $varName, [$expr->var->name => $pDec->getValue()]);
            } elseif ($expr instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
                $arrayDim = new \PhpTestBed\Node\Expr\ArrayDimFetch($expr);
                $this->printArrayDim($arrayDim->getVarName(), $arrayDim->getKeys(), $arrayDim->getResult());
            } else {
                $this->printEcho($expr);
            }
        }
    }

}
