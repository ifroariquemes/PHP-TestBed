<?php

namespace PhpTestBed\Node\Scalar;

use PhpTestBed\Stylizer;

class Encapsed extends \PhpTestBed\Node\NodeScalarAbstract
{

    public function __construct(\PhpParser\Node\Scalar\Encapsed $node)
    {
        $this->expr = '';
        $this->result = '';
        parent::__construct($node);
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getExpr()
    {
        return Stylizer::value($this->expr);
    }

    public function resolve()
    {
        foreach ($this->node->parts as $expr) {
            $nodeExpr = \PhpTestBed\Node\NodeLoader::load($expr);
            $this->result .= $nodeExpr->getResult();
            $this->expr .= $nodeExpr->getExpr();
        }
    }

    public function getMessage()
    {
        return \PhpTestBed\I18n::getInstance()->get('code.binary-op-var', [
                    'value' => Stylizer::type($this->getResult()),
                    'expr' => Stylizer::expression($this->getExpr()),
                    'where' => \PhpTestBed\Repository::getInstance()->showUsed()
        ]);
    }

}
