<?php

namespace PhpTestBed\Node\Expr\BinaryOp;

class LogicalXor extends NodeBinaryOpAbstract
{

    public function __construct(\PhpParser\NodeAbstract $statement)
    {
        $this->signal = 'xor';
        parent::__construct($statement);
    }

}
