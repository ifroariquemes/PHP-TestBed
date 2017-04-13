<?php

namespace PhpTestBed\Node;

/**
 * Defines the structure needed for any node that includes expressions 
 * on its scope.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
interface NodeExprInterface
{

    /**
     * Returns the expression for that statement.
     */
    public function getExpr();

    /**
     * Returns the output message for that statement.
     */
    public function getMessage();

    /**
     * Returns the result of the expression for that statement
     */
    public function getResult();
}
