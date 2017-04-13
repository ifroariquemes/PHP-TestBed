<?php

namespace PhpTestBed\Node\Expr\BinaryOp;

class LogicalAnd extends NodeBinaryOpAbstract
{

    public function __construct(\PhpParser\NodeAbstract $statement)
    {
        $this->signal = 'and';
        parent::__construct($statement);
    }

}
