<?php

namespace PhpTestBed\Node\Expr\AssignOp;

/**
 * Shift right within assing operation $a >>= 1.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @since Release 0.2.0
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class ShiftRight extends NodeAssignOpAbstract
{

    /**
     * Initializes object with a PhpParser AssignOp ShiftRight statemtent.
     * @param \PhpParser\Node\Expr\AssignOp\ShiftRight $statement The statement
     */
    public function __construct(\PhpParser\Node\Expr\AssignOp\ShiftRight $statement)
    {
        $this->signal = '>>';
        parent::__construct($statement);
    }

}
