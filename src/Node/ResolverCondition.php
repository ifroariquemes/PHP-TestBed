<?php

namespace PhpTestBed\Node;

use PhpTestBed\Node\Expr\BinaryOp;
use PhpTestBed\Node\Expr\Variable;
use PhpTestBed\Node\Expr\ConstFetch;
use PhpTestBed\Node\Expr\ArrayDimFetch;

class ResolverCondition
{

    public static function choose(\PhpParser\NodeAbstract $node)
    {
        switch (get_class($node)) {
            case \PhpParser\Node\Expr\Variable::class:
                return new Variable($node);
            case \PhpParser\Node\Expr\ConstFetch::class:
                return new ConstFetch($node);
            case \PhpParser\Node\Expr\ArrayDimFetch::class:
                return new ArrayDimFetch($node);
            default:
                if ($node instanceof \PhpParser\Node\Expr\BinaryOp) {
                    return new BinaryOp($node);
                }
        }
    }

}
