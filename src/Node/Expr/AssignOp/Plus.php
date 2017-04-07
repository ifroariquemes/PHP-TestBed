<?php

namespace PhpTestBed\Node\Expr\AssignOp;

class Plus extends NodeAssignOpAbstract
{

    public function __construct(\PhpParser\NodeAbstract $statement)
    {
        $this->signal = '+';
        parent::__construct($statement);
    }

}
