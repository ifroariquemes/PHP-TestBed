<?php

namespace PhpTestBed\Node;

use PhpTestBed\Node\NodeBaseAbstract;
use PhpTestBed\Node\NodeExprInterface;

/**
 * Base class for every PhpTestBed node that includes expressions on its scope.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
abstract class NodeExprAbstract extends NodeBaseAbstract implements NodeExprInterface
{

    /**
     * The expression left part
     * @var \PhpParser\NodeAbstract
     */
    protected $left;

    /**
     * The expression right part
     * @var \PhpParser\NodeAbstract
     */
    protected $right;

    /**
     * The expression in text format
     * @var string
     */
    protected $expr;

    /**
     * The expression result
     * @var mixed
     */
    protected $result;

    /**
     * Returns the result
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

}
