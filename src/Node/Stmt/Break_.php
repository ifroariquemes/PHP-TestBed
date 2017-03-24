<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class Break_ extends \PhpTestBed\Node\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\Break_ $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        $this->printMessage(I18n::getInstance()->get('code.break'));
        \PhpTestBed\ScriptCrawler::getInstance()->callBreak();
    }

}
