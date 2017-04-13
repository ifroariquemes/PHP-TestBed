<?php

namespace PhpTestBed\Node\Expr\AssignOp;

use PhpTestBed\Node\NodeLoader;
use PhpTestBed\Stylizer;
use PhpTestBed\I18n;

abstract class NodeAssignOpAbstract extends \PhpTestBed\Node\NodeExprAbstract
{

    protected $signal;

    public function getExpr()
    {
        return sprintf('%s %s %s'
                , Stylizer::variable("\${$this->node->var->name}")
                , Stylizer::operation($this->signal)
                , $this->expr
        );
    }

    public function getMessage()
    {
        $mVar = [
            'expr' => Stylizer::expression($this->getExpr()),
            'value' => Stylizer::value($this->getResult()),
            'where' => \PhpTestBed\Repository::getInstance()->showUsed()
        ];
        if (empty($mVar['where'])) {
            return I18n::getInstance()->get('code.binary-op', $mVar);
        }
        return I18n::getInstance()->get('code.binary-op-var', $mVar);
    }

    public function getResult()
    {
        return $this->result;
    }

    public function resolve()
    {
        $this->result = NodeLoader::load($this->node->var)->getResult();
        $nodeExpr = \PhpTestBed\Node\NodeLoader::load($this->node->expr);
        eval('$this->result ' . $this->signal
                . '= $nodeExpr->getResult();');
        $this->expr = $nodeExpr->getExpr();
        $this->printMessage(
                I18n::getInstance()->get('code.assign-op', [
                    'var' => Stylizer::variable("\${$this->node->var->name}"),
                    'value' => $this->getMessage()
                ])
        );
        \PhpTestBed\Repository::getInstance()->set($this->node->var->name, $this->result);
    }

}
