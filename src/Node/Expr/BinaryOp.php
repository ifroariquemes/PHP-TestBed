<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class BinaryOp
{

    private $expr;
    private $left;
    private $right;
    private $usedVars;
    private $usedConsts;
    private $result;

    public function __construct(\PhpParser\Node\Expr\BinaryOp $expr, array &$usedVars = array(), array &$usedConsts = array())
    {

        $this->expr = $expr;
        $this->usedVars = &$usedVars;
        $this->usedConsts = &$usedConsts;
        $this->left = $this->resolve($expr->left);
        $this->right = $this->resolve($expr->right);
    }

    private function resolve(\PhpParser\Node\Expr $bin)
    {
        if ($bin instanceof \PhpParser\Node\Expr\BinaryOp) {
            $nExpr = new BinaryOp($bin, $this->usedVars, $this->usedConsts);
            return $nExpr->getResult();
        } elseif ($bin instanceof \PhpParser\Node\Expr\Variable) {
            $this->usedVars[$bin->name] = \PhpTestBed\Repository::getInstance()->get($bin->name);
            return $this->usedVars[$bin->name];
        } elseif ($bin instanceof \PhpParser\Node\Scalar\Encapsed) {
            $enc = new \PhpTestBed\Node\Scalar\Encapsed($bin);
            $this->usedVars = array_merge($this->usedVars, $enc->getUsedVars());
            return $enc->getResult();
        } elseif ($bin instanceof \PhpParser\Node\Scalar) {
            return $bin->value;
        } elseif ($bin instanceof \PhpParser\Node\Expr\ConstFetch) {
            $this->usedConsts[$bin->name->parts[0]] = \PhpTestBed\Repository::getInstance()->getConst($bin->name->parts[0]);
            return $this->usedConsts[$bin->name->parts[0]];
        } else {
            \PhpTestBed\ScriptCrawler::getInstance()->printMessage(
                    Stylizer::systemException(
                            I18n::getInstance()->get('exceptions.node-not-implemented'
                                    , ['node' => get_class($bin)]) . ' (BinaryOp::resolve)'
                    )
            );
        }
    }

    public function message()
    {
        $mVar = [
            'expr' => Stylizer::expression($this->getExpr($this->expr)),
            'value' => Stylizer::value($this->getResult()),
            'where' => \PhpTestBed\Repository::showUsed($this->usedConsts, $this->usedVars)
        ];
        if (empty($mVar['where'])) {
            return I18n::getInstance()->get('code.binary-op', $mVar);
        }
        return I18n::getInstance()->get('code.binary-op-var', $mVar);
    }

    public function getExpr(\PhpParser\Node\Expr $expr = null)
    {
        $left = $this->getExprSide($expr->left, 'left');
        $right = $this->getExprSide($expr->right, 'right');
        $opSignal = Stylizer::operation($this->getSignal($expr));
        return "($left $opSignal $right)";
    }

    private function getExprSide($expr, $side)
    {
        if ($expr instanceof \PhpParser\Node\Expr\BinaryOp) {
            return $this->getExpr($expr);
        } else if ($expr instanceof \PhpParser\Node\Expr\Variable) {
            return Stylizer::variable("\${$expr->name}");
        } elseif ($expr instanceof \PhpParser\Node\Expr\ConstFetch) {
            return Stylizer::variable($expr->name);
        } elseif ($expr instanceof \PhpParser\Node\Scalar\Encapsed) {
            $enc = new \PhpTestBed\Node\Scalar\Encapsed($expr);
            return $enc->getExpr();
        }
        return Stylizer::type($this->$side);
    }

    public function getSignal(\PhpParser\Node\Expr\BinaryOp $expr)
    {
        switch (get_class($expr)) {
            case \PhpParser\Node\Expr\BinaryOp\BitwiseAnd::class:
                return '&';
            case \PhpParser\Node\Expr\BinaryOp\BitwiseOr::class:
                return '|';
            case \PhpParser\Node\Expr\BinaryOp\BitwiseXor::class:
                return '^';
            case \PhpParser\Node\Expr\BinaryOp\BooleanAnd::class:
                return '&&';
            case \PhpParser\Node\Expr\BinaryOp\BooleanOr::class:
                return '||';
            case \PhpParser\Node\Expr\BinaryOp\Coalesce::class:
                return '??';
            case \PhpParser\Node\Expr\BinaryOp\Concat::class:
                return '.';
            case \PhpParser\Node\Expr\BinaryOp\Div::class:
                return '/';
            case \PhpParser\Node\Expr\BinaryOp\Equal::class:
                return '==';
            case \PhpParser\Node\Expr\BinaryOp\Greater::class:
                return '&gt;';
            case \PhpParser\Node\Expr\BinaryOp\GreaterOrEqual::class:
                return '&gt;=';
            case \PhpParser\Node\Expr\BinaryOp\Identical::class:
                return '===';
            case \PhpParser\Node\Expr\BinaryOp\Minus::class:
                return '-';
            case \PhpParser\Node\Expr\BinaryOp\Mod::class:
                return '%';
            case \PhpParser\Node\Expr\BinaryOp\Mul::class:
                return '*';
            case \PhpParser\Node\Expr\BinaryOp\NotEqual::class:
                return '!=';
            case \PhpParser\Node\Expr\BinaryOp\NotIdentical::class:
                return '!==';
            case \PhpParser\Node\Expr\BinaryOp\Plus::class:
                return '+';
            case \PhpParser\Node\Expr\BinaryOp\ShiftLeft::class:
                return '&lt;&lt;';
            case \PhpParser\Node\Expr\BinaryOp\ShiftRight::class:
                return '&gt;&gt;';
            case \PhpParser\Node\Expr\BinaryOp\SmallerOrEqual::class:
                return '&lt;=';
            case \PhpParser\Node\Expr\BinaryOp\Smaller::class:
                return '&lt;';
            case \PhpParser\Node\Expr\BinaryOp\Spaceship::class:
                return '&lt;=&gt;';
            case \PhpParser\Node\Expr\BinaryOp\LogicalAnd::class:
            case \PhpParser\Node\Expr\BinaryOp\LogicalOr::class:
            case \PhpParser\Node\Expr\BinaryOp\LogicalXor::class:
                trigger_error("Can it be possible?", E_USER_NOTICE);
                break;
        }
    }

    public function getResult()
    {
        if (empty($this->result)) {
            switch (get_class($this->expr)) {
                case \PhpParser\Node\Expr\BinaryOp\BitwiseAnd::class:
                    return $this->left & $this->right;
                case \PhpParser\Node\Expr\BinaryOp\BitwiseOr::class:
                    return $this->left | $this->right;
                case \PhpParser\Node\Expr\BinaryOp\BitwiseXor::class:
                    return $this->left ^ $this->right;
                case \PhpParser\Node\Expr\BinaryOp\BooleanAnd::class:
                    return $this->left && $this->right;
                case \PhpParser\Node\Expr\BinaryOp\BooleanOr::class:
                    return $this->left || $this->right;
                case \PhpParser\Node\Expr\BinaryOp\Coalesce::class:
                    return $this->left ?? $this->right;
                case \PhpParser\Node\Expr\BinaryOp\Concat::class:
                    return $this->left . $this->right;
                case \PhpParser\Node\Expr\BinaryOp\Div::class:
                    return $this->left / $this->right;
                case \PhpParser\Node\Expr\BinaryOp\Equal::class:
                    return $this->left == $this->right;
                case \PhpParser\Node\Expr\BinaryOp\Greater::class:
                    return $this->left > $this->right;
                case \PhpParser\Node\Expr\BinaryOp\GreaterOrEqual::class:
                    return $this->left >= $this->right;
                case \PhpParser\Node\Expr\BinaryOp\Identical::class:
                    return $this->left === $this->right;
                case \PhpParser\Node\Expr\BinaryOp\Minus::class:
                    return $this->left - $this->right;
                case \PhpParser\Node\Expr\BinaryOp\Mod::class:
                    return $this->left % $this->right;
                case \PhpParser\Node\Expr\BinaryOp\Mul::class:
                    return $this->left * $this->right;
                case \PhpParser\Node\Expr\BinaryOp\NotEqual::class:
                    return $this->left != $this->right;
                case \PhpParser\Node\Expr\BinaryOp\NotIdentical::class:
                    return $this->left !== $this->right;
                case \PhpParser\Node\Expr\BinaryOp\Plus::class:
                    return $this->left + $this->right;
                case \PhpParser\Node\Expr\BinaryOp\ShiftLeft::class:
                    return $this->left * pow(2, $this->right);
                case \PhpParser\Node\Expr\BinaryOp\ShiftRight::class:
                    return $this->left / pow(2, $this->right);
                case \PhpParser\Node\Expr\BinaryOp\Smaller::class:
                    return $this->left < $this->right;
                case \PhpParser\Node\Expr\BinaryOp\SmallerOrEqual::class:
                    return $this->left <= $this->right;
                case \PhpParser\Node\Expr\BinaryOp\Spaceship::class:
                    return $this->left <=> $this->right;
                case \PhpParser\Node\Expr\BinaryOp\LogicalAnd::class:
                case \PhpParser\Node\Expr\BinaryOp\LogicalOr::class:
                case \PhpParser\Node\Expr\BinaryOp\LogicalXor::class:
                    trigger_error("Can it be possible?", E_USER_ERROR);
            }
        }
        return $this->result;
    }

}
