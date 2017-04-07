<?php

namespace PhpTestBed\Node;

/**
 * Base class for every PhpTestBed node that is scalar based on primitive data types.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
abstract class NodePrimitiveScalarAbstract extends \PhpTestBed\Node\NodeScalarAbstract
{

    /**
     * Returns the expression message
     * @return string
     */
    public function getExpr(): string
    {
        return \PhpTestBed\Stylizer::type($this->getResult());
    }

    /**
     * Returns the output message
     * @return string
     */
    public function getMessage(): string
    {
        return \PhpTestBed\I18n::getInstance()->get('code.scalar', [
                    'value' => \PhpTestBed\Stylizer::type($this->getResult())
        ]);
    }

    /**
     * Returns the result
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Resolve the statement algorithm
     */
    public function resolve()
    {
        $this->result = $this->node->value;
    }

}
