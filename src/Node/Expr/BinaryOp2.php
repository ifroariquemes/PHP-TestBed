<?php

namespace PhpTestBed\Node\Expr;

class BinaryOp2
{

    private $usedVars = array();

    public function resolve(\PhpParser\Node\Expr\BinaryOp $binary)
    {
        $valueLeft = $this->getResult($binary->left);
        $valueRight = $this->getResult($binary->right);
        switch (get_class($binary)) {

            case \PhpParser\Node\Expr\BinaryOp\BitwiseAnd::class:
                return $valueLeft & $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\BitwiseOr::class:
                return $valueLeft | $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\BitwiseXor::class:
                return $valueLeft ^ $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\BooleanAnd::class:
                return $valueLeft && $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\BooleanOr::class:
                return $valueLeft || $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\Coalesce::class:
                return $valueLeft ?? $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\Concat::class:
                return $valueLeft . $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\Div::class:
                return $valueLeft / $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\Equal::class:
                return $valueLeft == $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\Greater::class:
                return $valueLeft > $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\GreaterOrEqual::class:
                return $valueLeft >= $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\Identical::class:
                return $valueLeft === $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\Minus::class:
                return $valueLeft - $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\Mod::class:
                return $valueLeft % $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\Mul::class:
                return $valueLeft * $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\NotEqual::class:
                return $valueLeft != $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\NotIdentical::class:
                return $valueLeft !== $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\Plus::class:
                return $valueLeft + $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\ShiftLeft::class:
                return $valueLeft * pow(2, $valueRight);;
            case \PhpParser\Node\Expr\BinaryOp\ShiftRight::class:
                return $valueLeft / pow(2, $valueRight);
            case \PhpParser\Node\Expr\BinaryOp\Smaller::class:
                return $valueLeft < $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\SmallerOrEqual::class:
                return $valueLeft <= $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\Spaceship::class:
                return $valueLeft <=> $valueRight;
            case \PhpParser\Node\Expr\BinaryOp\LogicalAnd::class:
            case \PhpParser\Node\Expr\BinaryOp\LogicalOr::class:
            case \PhpParser\Node\Expr\BinaryOp\LogicalXor::class:
                trigger_error("Can it be possible?", E_USER_ERROR);
        }
    }

    public function getExpression(\PhpParser\Node\Expr\BinaryOp $binary)
    {
        $valueLeft = $this->getExpressionResult($binary->left);
        $valueRight = $this->getExpressionResult($binary->right);
        switch (get_class($binary)) {
            case \PhpParser\Node\Expr\BinaryOp\BitwiseAnd::class:
                return "($valueLeft & $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\BitwiseOr::class:
                return "($valueLeft | $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\BitwiseXor::class:
                return "($valueLeft ^ $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\BooleanAnd::class:
                return "($valueLeft && $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\BooleanOr::class:
                return "($valueLeft || $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\Coalesce::class:
                return "($valueLeft ?? $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\Concat::class:
                return "($valueLeft . $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\Div::class:
                return "($valueLeft / $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\Equal::class:
                return "($valueLeft == $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\Greater::class:
                return "($valueLeft > $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\GreaterOrEqual::class:
                return "($valueLeft >= $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\Identical::class:
                return "($valueLeft === $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\Minus::class:
                return "($valueLeft - $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\Mod::class:
                return "($valueLeft % $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\Mul::class:
                return "($valueLeft * $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\NotEqual::class:
                return "($valueLeft != $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\NotIdentical::class:
                return "($valueLeft !== $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\Plus::class:
                return "($valueLeft + $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\ShiftLeft::class:
                return "($valueLeft << $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\ShiftRight::class:
                return "($valueLeft >> $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\SmallerOrEqual::class:
                return "($valueLeft <= $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\Smaller::class:
                return "($valueLeft <= $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\Spaceship::class:
                return "($valueLeft <=> $valueRight)";
            case \PhpParser\Node\Expr\BinaryOp\LogicalAnd::class:
            case \PhpParser\Node\Expr\BinaryOp\LogicalOr::class:
            case \PhpParser\Node\Expr\BinaryOp\LogicalXor::class:
                trigger_error("Can it be possible?", E_USER_NOTICE);
                break;
        }
    }

    private function getResult(\PhpParser\Node\Expr $binarySide)
    {
        if ($binarySide instanceof \PhpParser\Node\Expr\BinaryOp) {
            return $this->resolve($binarySide);
        } else if ($binarySide instanceof \PhpParser\Node\Expr\Variable) {
            return \PhpTestBed\Repository::getInstance()->get($binarySide->name);
        } else if ($binarySide instanceof \PhpParser\Node\Scalar) {
            return $binarySide->value;
        }
    }

    private function getExpressionResult(\PhpParser\Node\Expr $binarySide)
    {
        if ($binarySide instanceof \PhpParser\Node\Expr\BinaryOp) {
            return $this->getExpression($binarySide);
        } else if ($binarySide instanceof \PhpParser\Node\Expr\Variable) {
            $this->usedVars[$binarySide->name] = \PhpTestBed\Repository
                    ::getInstance()->get($binarySide->name);
            return sprintf('$%s', $binarySide->name);
        } else if ($binarySide instanceof \PhpParser\Node\Scalar) {
            return $binarySide->value;
        }
    }

    public function getUsedVars()
    {
        return $this->usedVars;
    }

}
