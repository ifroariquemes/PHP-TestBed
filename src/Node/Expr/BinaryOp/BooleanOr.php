<?php

namespace PhpTestBed\Node\Expr\BinaryOp;

class BooleanOr extends NodeBinaryOpAbstract
{

    public function __construct(\PhpParser\NodeAbstract $statement)
    {
        $this->signal = '||';
        parent::__construct($statement);
    }

}
