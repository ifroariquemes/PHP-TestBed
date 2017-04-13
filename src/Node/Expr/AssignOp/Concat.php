<?php

namespace PhpTestBed\Node\Expr\AssignOp;

/**
 * Concat within assing operation $a .= "hello".
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @since Release 0.2.0
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Concat extends NodeAssignOpAbstract
{

    /**
     * Initializes object with a PhpParser AssignOp Concat statemtent.
     * @param \PhpParser\Node\Expr\AssignOp\Concat $statement The statement
     */
    public function __construct(\PhpParser\Node\Expr\AssignOp\Concat $statement)
    {
        $this->signal = '.';
        parent::__construct($statement);
    }

}
