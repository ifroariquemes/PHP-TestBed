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

    protected function resolve()
    {
        foreach ($this->node->consts as $expr) {
            if ($expr->value instanceof \PhpParser\Node\Scalar) {
                $mVar = [
                    'const' => Stylizer::variable($expr->name),
                    'value' => Stylizer::value($expr->value->value)
                ];
                $this->printMessage(I18n::getInstance()->get('code.const', $mVar));
                \PhpTestBed\Repository::getInstance()->setConst($expr->name, $expr->value->value);
            }
        }
    }

}
