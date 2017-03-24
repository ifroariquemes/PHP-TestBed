<?php

namespace PhpTestBed\Node;

interface ResolverConditionInterface
{

    public function getExpr();

    public function message();

    public function getResult();
}
