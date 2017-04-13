<?php

namespace PhpTestBed\Node\Scalar;

/**
 * Scalar for string parts inside a encapsed string
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class EncapsedStringPart extends \PhpTestBed\Node\NodeScalarAbstract
{

    public function getExpr()
    {
        return str_replace('\'', '', parent::getExpr());
    }

}
