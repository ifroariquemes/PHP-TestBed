<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\Stylizer;
use PhpTestBed\I18n;

class ConstFetch extends \PhpTestBed\Node\NodeUsableAbstract
{

    private $value;
    private $constName;

    public function __construct(\PhpParser\Node\Expr\ConstFetch $node)
    {
        $this->constName = $node->name->parts[0];
        parent::__construct($node);
    }

    public function resolve()
    {
        switch ($this->constName) {
            case 'true':
                $this->value = true;
                break;
            case 'false':
                $this->value = false;
                break;
            default:
                $this->value = \PhpTestBed\Repository::getInstance()->getConst($this->constName);
                break;
        }
    }

    public function getExpr()
    {
        return Stylizer::constant($this->constName);
    }

    public function getMessage()
    {
        $mVar = [
            'expr' => Stylizer::expression($this->getExpr()),
            'value' => Stylizer::value($this->value),
            'where' => \PhpTestBed\Repository::getInstance()->showUsed()
        ];
        return I18n::getInstance()->get('code.binary-op-var', $mVar);
    }

    public function getResult()
    {
        return $this->value;
    }

    public function addUsage()
    {
        \PhpTestBed\Repository::getInstance()->addUsedConstant($this->constName, $this->value);
    }

}
