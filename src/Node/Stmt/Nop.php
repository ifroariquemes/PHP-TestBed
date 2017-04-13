<?php

namespace PhpTestBed\Node\Stmt;

/**
 * Nop statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.1.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Nop extends \PhpTestBed\Node\NodeBaseAbstract
{

    /**
     * Initializes object with a PhpParser Nop statemtent.
     * @param \PhpParser\Node\Stmt\Nop $node The statement
     */
    public function __construct(\PhpParser\Node\Stmt\Nop $node)
    {
        parent::__construct($node);
    }

    /**
     * Resolves Nop. What is a Nop?
     */
    public function resolve()
    {
        
    }

}
