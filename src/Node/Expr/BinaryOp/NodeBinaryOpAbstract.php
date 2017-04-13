<?php

namespace PhpTestBed\Node\Expr\BinaryOp;

use PhpTestBed\Node\NodeLoader;
use PhpTestBed\Stylizer;
use PhpTestBed\I18n;

abstract class NodeBinaryOpAbstract extends \PhpTestBed\Node\NodeExprAbstract
{

    protected $signal;

    public function getExpr()
    {
        $left = $this->left->getExpr();
        $right = $this->right->getExpr();
        $opSignal = Stylizer::operation($this->signal);
        return "($left $opSignal $right)";
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
        $this->left = NodeLoader::load($this->node->left);
        $this->right = NodeLoader::load($this->node->right);
        eval('$this->result = $this->left->getResult() ' .
                $this->signal .
                ' $this->right->getResult();');
    }

}
