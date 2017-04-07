<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\Stylizer;
use PhpTestBed\I18n;
use PhpTestBed\Node\NodeLoader;

class ArrayDimFetch extends \PhpTestBed\Node\NodeUsableAbstract
{

    private $varName;
    private $value;
    private $blockUsage;

    public function __construct(\PhpParser\Node\Expr\ArrayDimFetch $node, $blockUsage = false)
    {
        $this->blockUsage = $blockUsage;
        parent::__construct($node);
    }

    public function resolve()
    {
        if ($this->node->var instanceof \PhpParser\Node\Expr\ArrayDimFetch) {
            $newDim = new ArrayDimFetch($this->node->var, true);
            $item = $newDim->getResult();
            $this->varName = $newDim->getVarName();
        } else {
            $this->varName = $this->node->var->name;
            $item = \PhpTestBed\Repository::getInstance()->get($this->varName);
        }
        $this->value = $item[NodeLoader::load($this->node->dim)->getResult()];
    }

    public function getExpr()
    {
        return sprintf('%s%s%s%s'
                , ($this->node->var instanceof \PhpParser\Node\Expr\ArrayDimFetch) ?
                (new ArrayDimFetch($this->node->var, true))->getExpr() :
                Stylizer::variable($this->varName)
                , Stylizer::operation('[')
                , NodeLoader::load($this->node->dim)->getExpr()
                , Stylizer::operation(']')
        );
    }

    public function getMessage()
    {
        $mVar = [
            'expr' => Stylizer::expression($this->getExpr()),
            'value' => Stylizer::value($this->value),
            'where' => \PhpTestBed\Repository::getInstance()->showUsed()
        ];
        return I18n::getInstance()->get('code.binary-op-var', $mVar);
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
            array_push($keys, NodeLoader::load($item->dim)->getResult());
            $item = $item->var;
        }
        return array_reverse($keys);
    }

    public function addUsage()
    {
        if (!$this->blockUsage) {
            \PhpTestBed\Repository::getInstance()
                    ->addUsedArray($this->getVarName(), $this->getKeys(), $this->getResult());
        }
    }

}
