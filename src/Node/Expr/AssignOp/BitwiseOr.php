<?php

namespace PhpTestBed\Node\Expr\AssignOp;

/**
 * Bitwise Or within assing operation $a |= 1.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @since Release 0.2.0
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class BitwiseOr extends NodeAssignOpAbstract
{

    /**
     * Initializes object with a PhpParser AssignOp BitwiseOr statemtent.
     * @param \PhpParser\Node\Expr\AssignOp\BitwiseOr $statement The statement
     */
    public function __construct(\PhpParser\Node\Expr\AssignOp\BitwiseOr $statement)
    {
        $this->signal = '|';
        parent::__construct($statement);
    }

}
