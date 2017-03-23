<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\Stylizer;
use PhpTestBed\I18n;

class ArrayDimFetch extends \PhpTestBed\Node\ResolverConditionAbstract
{

    private $varName;
    private $value;

    public function __construct(\PhpParser\Node\Expr\ArrayDimFetch $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        if ($this->node->var instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
            $newDim = new ArrayDimFetch($this->node->var);
            $item = $newDim->getResult();
            $this->varName = $newDim->getVarName();
        } else {
            $this->varName = $this->node->var->name;
            $item = \PhpTestBed\Repository::getInstance()->get($this->varName);
        }
        $this->value = $item[$this->node->dim->value];
    }

    public function getExpr()
    {
        if ($this->node->var instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
            return sprintf('%s[%s]'
                    , (new ArrayDimFetch($this->node->var))->getExpr()
                    , Stylizer::type($this->node->dim->value)
            );
        }
        return sprintf('%s[%s]'
                , Stylizer::variable($this->varName)
                , Stylizer::type($this->node->dim->value)
        );
    }

    public function message()
    {
        $mVar = [
            'expr' => Stylizer::expression("({$this->getExpr()})"),
            'value' => Stylizer::value($this->value)
        ];
        return I18n::getInstance()->get('code.binary-op', $mVar);
    }

    public function getResult()
    {
        return $this->value;
    }

    public function getVarName()
    {
        return $this->varName;
    }

    public function getKeys()
    {
        $keys = array();
        $item = $this->node;
        while ($item instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
            array_push($keys, $item->dim->value);
            $item = $item->var;
        }
        return array_reverse($keys);
    }

}
