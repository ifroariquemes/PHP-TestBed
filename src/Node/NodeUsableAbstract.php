<?php

namespace PhpTestBed\Node;

/**
 * Base class for every PhpTestBed node that generates a repository entry.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
abstract class NodeUsableAbstract extends NodeExprAbstract implements NodeUsableInterface
{

    /**
     * Initializes a new statement then adds it usage information 
     * within repository.
     * @param \PhpParser\NodeAbstract $statement The statement
     */
    public function __construct(\PhpParser\NodeAbstract $statement)
    {
        parent::__construct($statement);
        $this->addUsage();
    }    
    
    public function getName()
    {
        return $this->node->var->name;
    }

}
