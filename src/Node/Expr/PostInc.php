<?php

namespace PhpTestBed\Node\Expr;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class PostInc extends \PhpTestBed\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Expr\PostInc $statement)
    {
        parent::__construct($statement);
    }

    protected function resolve()
    {
        $currentValue = \PhpTestBed\Repository::getInstance()->get($this->node->var->name);
        $mVar = [
            'var' => Stylizer::variable("\${$this->node->var->name}"),
            'value' => Stylizer::value(++$currentValue)
        ];
        $this->printMessage(I18n::getInstance()->get('code.post-inc', $mVar));
        \PhpTestBed\Repository::getInstance()
                ->set($this->node->var->name, $currentValue);
    }

}
