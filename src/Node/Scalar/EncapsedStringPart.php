<?php

namespace PhpTestBed\Node\Scalar;

class EncapsedStringPart extends \PhpTestBed\Node\NodePrimitiveScalarAbstract
{

    public function getExpr()
    {
        return str_replace('\'', '', parent::getExpr());
    }

}
