<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class Const_ extends \PhpTestBed\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\Const_ $node)
    {
        parent::__construct($node);
    }

    private function printConst($name, $value)
    {
        $mVar = [
            'const' => Stylizer::variable($name),
            'value' => Stylizer::value($value)
        ];
        $this->printMessage(I18n::getInstance()->get('code.const', $mVar));
    }

    protected function resolve()
    {
        foreach ($this->node->consts as $expr) {
            if ($expr->value instanceof \PhpParser\Node\Scalar) {
                $this->printConst($expr->name, $expr->value->value);
                \PhpTestBed\Repository::getInstance()->setConst($expr->name, $expr->value->value);
            }
        }
    }

}
