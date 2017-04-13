<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\Stylizer;
use PhpTestBed\I18n;
use PhpTestBed\Node\NodeLoader;

/**
 * An array single item.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class ArrayDimFetch extends \PhpTestBed\Node\NodeUsableAbstract
{

    /**
     * Variable name where the array is stored.
     * @var string
     */
    private $varName;

    /**
     * Prevents the instance add usage for the same dim call. It should be true
     * if dim is a multidimensional array.
     * @var bool 
     */
    private $blockUsage;

    /**
     * Initializes object with a PhpParser ArrayDimFetch statemtent.
     * @param \PhpParser\Node\Expr\ArrayDimFetch $node The statement
     * @param bool $blockUsage If addUsage will be blocked
     */
    public function __construct(\PhpParser\Node\Expr\ArrayDimFetch $node, $blockUsage = false)
    {
        $this->blockUsage = $blockUsage;
        parent::__construct($node);
    }

    /**
     * Resolves the ArrayDimFetch statement by getting its value at repository.
     */
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
        $this->result = $item[NodeLoader::load($this->node->dim)->getResult()];
    }

    /**
     * Returns the expression message.
     * @return string
     */
    public function getExpr(): string
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

    /**
     * Returns the output message.
     * @return string
     */
    public function getMessage(): string
    {
        $mVar = [
            'expr' => Stylizer::expression($this->getExpr()),
            'value' => Stylizer::value($this->result),
            'where' => \PhpTestBed\Repository::getInstance()->showUsed()
        ];
        return I18n::getInstance()->get('code.binary-op-var', $mVar);
    }

    /**
     * Returns the variable name where dim is stored.
     * @return string
     */
    public function getVarName(): string
    {
        return $this->varName;
    }

    /**
     * Returns the used keys to get that dim.
     * @return mixed
     */
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

    /**
     * Adds the usage of that dim into repository.
     */
    public function addUsage()
    {
        if (!$this->blockUsage) {
            \PhpTestBed\Repository::getInstance()
                    ->addUsedArray($this->getVarName(), $this->getKeys(), $this->getResult());
        }
    }

}
