<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;
use PhpTestBed\Repository;

/**
 * Statement used when assigning a value to a variable.
 * @package PhpTestBed
 * @copyright (c) 2017, Federal Institute of Rondonia
 * @license https://creativecommons.org/licenses/by/4.0/ CC BY 4.0
 * @since Release 0.1.0 
 * @author Natanael Simoes <natanael.simoes@ifro.edu.br>
 * @link https://github.com/ifroariquemes/PHP-TestBed Github repository
 */
class Assign extends \PhpTestBed\Node\NodeExprAbstract
{

    /**
     * @var \PhpParser\NodeAbstract
     */
    private $assignNode;

    /**
     * Initializes object with a PhpParser Assign statemtent.
     * @param \PhpParser\Node\Expr\Assign $statement The statement
     */
    public function __construct(\PhpParser\Node\Expr\Assign $statement)
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
        return I18n::getInstance()->get('code.binary-op', [
                    'value' => Stylizer::type($this->result),
                    'expr' => $this->assignNode->getExpr(),
        ]);
    }

    /**
     * Resolves the Assign statement putting the expression node result into
     * variable at Repository.
     */
    public function resolve()
    {
        $this->assignNode = \PhpTestBed\Node\NodeLoader::load($this->node->var);
        $nodeExpr = \PhpTestBed\Node\NodeLoader::load($this->node->expr);
        if (!is_null($nodeExpr)) {
            $this->result = $nodeExpr->getResult();
            $this->expr = $nodeExpr->getExpr();
            $dims = array();
            preg_match_all("/\[(.*?)\]/", $this->assignNode->getExpr(), $dims);
            Repository::getInstance()->set($this->assignNode->getName(), $this->result, $dims[1]);
            $this->printMessage(
                    I18n::getInstance()->get('code.assign-op', [
                        'var' => $this->assignNode->getExpr(),
                        'value' => $nodeExpr->getMessage()
                    ])
            );
        }
    }

}
