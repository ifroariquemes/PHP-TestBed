<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class Nop extends \PhpTestBed\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\Nop $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        
    }

}
