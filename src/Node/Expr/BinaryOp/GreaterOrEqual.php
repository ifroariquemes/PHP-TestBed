<?php

namespace PhpTestBed\Node\Expr\BinaryOp;

class GreaterOrEqual extends NodeBinaryOpAbstract
{

    public function __construct(\PhpParser\NodeAbstract $statement)
    {
        $this->signal = '>=';
        parent::__construct($statement);
    }

}
