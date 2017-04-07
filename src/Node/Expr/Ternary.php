<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;
use PhpTestBed\Repository;
use PhpTestBed\Node\NodeLoader;

class Ternary extends \PhpTestBed\Node\NodeExprAbstract
{

    public function __construct(\PhpParser\Node\Expr\Ternary $statement)
    {
        parent::__construct($statement);
    }

    public function getExpr()
    {
        return $this->expr;
    }

    public function getMessage()
    {
        return I18n::getInstance()->get(
                        (preg_match("/[a-zA-Z]/i", $this->expr)) ?
                        'code.binary-op-var' : 'code.binary-op', [
                    'value' => Stylizer::type($this->result),
                    'expr' => Stylizer::expression($this->expr),
                    'where' => Repository::getInstance()->showUsed()
        ]);
    }

    public function resolve()
    {
        $nodeCond = NodeLoader::load($this->node->cond);
        $nodeIf = NodeLoader::load($this->node->if);
        $nodeElse = NodeLoader::load($this->node->else);
        if ($nodeCond->getResult()) {
            $this->result = $nodeIf->getResult();
        } else {
            $this->result = $nodeElse->getResult();
        }
        $this->expr = sprintf('%s ? %s : %s'
                , $nodeCond->getExpr()
                , $nodeIf->getExpr()
                , $nodeElse->getExpr());
    }

}
