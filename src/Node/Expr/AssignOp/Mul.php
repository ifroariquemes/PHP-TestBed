<?php

namespace PhpTestBed\Node\Expr\AssignOp;

/**
 * Multiplication within assing operation $a *= 1.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @since Release 0.2.0
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Mul extends NodeAssignOpAbstract
{

    /**
     * Initializes object with a PhpParser AssignOp Mul statemtent.
     * @param \PhpParser\Node\Expr\AssignOp\Mul $statement The statement
     */
    public function __construct(\PhpParser\Node\Expr\AssignOp\Mul $statement)
    {
        $this->signal = '*';
        parent::__construct($statement);
    }

}
