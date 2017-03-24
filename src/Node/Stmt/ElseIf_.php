<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;

class ElseIf_ extends \PhpTestBed\Node\ResolverAbstract
{

    private $condition;

    public function __construct(\PhpParser\Node\Stmt\ElseIf_ $node)
    {
        parent::__construct($node);
    }

    private function printIfCond()
    {
        $this->printMessage(
                I18n::getInstance()->get('code.if-cond') . ' ' .
                $this->condition->message()
        );
    }

    protected function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        $this->condition = \PhpTestBed\Node\ResolverCondition::choose($this->node->cond);
        $this->printIfCond();
        if ($this->condition->getResult()) {
            $scriptCrawler->crawl($this->node->stmts);
            $this->setResolveState(true);
        } else {
            $this->setResolveState(false);
        }
    }

}
