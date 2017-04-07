<?php

namespace PhpTestBed\Node;

use PhpTestBed\Node\NodeBaseAbstract;
use PhpTestBed\Node\NodeExprInterface;

/**
 * Base class for every PhpTestBed node that is scalar.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
abstract class NodeScalarAbstract extends NodeBaseAbstract implements NodeExprInterface
{

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

}
