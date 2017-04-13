<?php

namespace PhpTestBed\Node\Scalar;

use PhpTestBed\Stylizer;

/**
 * Scalar for encapsed strings
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license http://gnu.org/licenses/lgpl.txt LGPL-3.0+
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Encapsed extends \PhpTestBed\Node\NodeScalarAbstract
{

    /**
     * Initializes object with a PhpParser Encapsed statemtent.
     * @param \PhpParser\Node\Scalar\Encapsed $node The statement
     */
    public function __construct(\PhpParser\Node\Scalar\Encapsed $node)
    {
        $this->expr = '';
        $this->result = '';
        parent::__construct($node);
    }

    /**
     * Returns the expression message.
     * @return string
     */
    public function getExpr(): string
    {
        return Stylizer::value($this->expr);
    }

    /**
     * Resolves the encapsed statement concating the result of each part and
     * their expressions.
     */
    public function resolve()
    {
        foreach ($this->node->parts as $expr) {
            $nodeExpr = \PhpTestBed\Node\NodeLoader::load($expr);
            $this->result .= $nodeExpr->getResult();
            $this->expr .= $nodeExpr->getExpr();
        }
    }

    /**
     * Returns the output message.
     * @return string
     */
    public function getMessage(): string
    {
        return \PhpTestBed\I18n::getInstance()->get('code.binary-op-var', [
                    'value' => Stylizer::type($this->getResult()),
                    'expr' => Stylizer::expression($this->getExpr()),
                    'where' => \PhpTestBed\Repository::getInstance()->showUsed()
        ]);
    }

}
