<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;
use PhpTestBed\Repository;
use PhpTestBed\Node\NodeLoader;

/**
 * A ternary if statement.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.2.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Ternary extends \PhpTestBed\Node\NodeExprAbstract
{

    /**
     * Initializes object with a PhpParser Ternary statemtent.
     * @param \PhpParser\Node\Expr\Ternary $statement The statement
     */
    public function __construct(\PhpParser\Node\Expr\Ternary $statement)
    {
        parent::__construct($statement);
    }

    /**
     * Returns the expression message.
     * @return string
     */
    public function getExpr(): string
    {
        return $this->expr;
    }

    /**
     * Returns the output message.
     * @return string
     */
    public function getMessage(): string
    {
        return I18n::getInstance()->get(
                        (preg_match("/[a-zA-Z]/i", $this->expr)) ?
                        'code.binary-op-var' : 'code.binary-op', [
                    'value' => Stylizer::type($this->result),
                    'expr' => Stylizer::expression($this->expr),
                    'where' => Repository::getInstance()->showUsed()
        ]);
    }

    /**
     * Evaluate the statement condition. If true, then gets the if node result,
     * if false, then gets the else node result.
     */
    public function resolve()
    {
        $nodeCond = NodeLoader::load($this->node->cond);
        $nodeIf = NodeLoader::load($this->node->if);
        $nodeElse = NodeLoader::load($this->node->else);
        $this->result = $nodeCond->getResult() ?
                $nodeIf->getResult() : $nodeElse->getResult();
        $this->expr = sprintf('%s ? %s : %s'
                , $nodeCond->getExpr()
                , $nodeIf->getExpr()
                , $nodeElse->getExpr());
    }

}
