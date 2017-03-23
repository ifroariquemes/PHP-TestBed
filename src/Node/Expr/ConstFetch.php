<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\Stylizer;
use PhpTestBed\I18n;

class ConstFetch extends \PhpTestBed\Node\ResolverConditionAbstract
{

    private $value;

    public function __construct(\PhpParser\Node\Expr\ConstFetch $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        switch ($this->node->name->parts[0]) {
            case 'true':
                $this->value = true;
                break;
            case 'false':
                $this->value = false;
                break;
            default:
                $this->value = \PhpTestBed\Repository::getInstance()->getConst($this->node->name->parts[0]);
                break;
        }
    }

    public function getExpr()
    {
        return Stylizer::constant($this->node->name->parts[0]);
    }

    public function message()
    {
        $mVar = [
            'expr' => Stylizer::expression($this->getExpr()),
            'value' => Stylizer::value($this->value),
            'where' => \PhpTestBed\Repository::showUsed([], [], [])
        ];
        return I18n::getInstance()->get('code.binary-op-var', $mVar);
    }

    public function getResult()
    {
        return $this->value;
    }

}
