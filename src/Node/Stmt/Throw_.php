<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;

class Throw_ extends \PhpTestBed\Node\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\Throw_ $node)
    {
        parent::__construct($node);
    }
    
    protected function printEnterMessage()
    {
        $this->printMessage(I18n::getInstance()->get('code.throw'));
    }

    protected function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        if (!$scriptCrawler->callThrow($this->node)) {
            $scriptCrawler->callExit();
        }
    }

}
