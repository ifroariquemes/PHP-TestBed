<?php

namespace PhpTestBed\Node\Stmt;

class Nop extends \PhpTestBed\Node\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\Nop $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        
    }

}
