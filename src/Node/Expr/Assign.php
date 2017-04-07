<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;
use PhpTestBed\Repository;

class Assign extends \PhpTestBed\Node\NodeExprAbstract
{

    private $varName;

    public function __construct(\PhpParser\Node\Expr\Assign $statement)
    {
        parent::__construct($statement);
    }

    public function getExpr()
    {
        return $this->expr;
    }

    public function getMessage()
    {
        return I18n::getInstance()->get('code.binary-op', [
                    'value' => Stylizer::type($this->result),
                    'expr' => Stylizer::expression(Stylizer::variable("\${$this->varName}")),
        ]);
    }

    protected function printMessage($message, $overrideLine = 0)
    {
        parent::printMessage(I18n::getInstance()->get('code.assign-op', [
                    'var' => Stylizer::variable("\${$this->varName}"),
                    'value' => $message
                ]), $overrideLine);
    }

    public function resolve()
    {
        $this->varName = $this->node->var->name;
        $nodeExpr = \PhpTestBed\Node\NodeLoader::load($this->node->expr);
        if (!is_null($nodeExpr)) {
            $this->result = $nodeExpr->getResult();
            $this->expr = $nodeExpr->getExpr();
            Repository::getInstance()->set($this->varName, $this->result);
            $this->printMessage($nodeExpr->getMessage());
        }
    }

}
