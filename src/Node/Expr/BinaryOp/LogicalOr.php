<?php

namespace PhpTestBed\Node\Expr\BinaryOp;

class LogicalOr extends NodeBinaryOpAbstract
{

    public function __construct(\PhpParser\NodeAbstract $statement)
    {
        $this->signal = 'or';
        parent::__construct($statement);
    }

}
