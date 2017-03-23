<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;

class Do_ extends \PhpTestBed\Node\ResolverAbstract
{

    private $condition;

    public function __construct(\PhpParser\Node\Stmt\Do_ $node)
    {
        parent::__construct($node);
    }

    protected function printEnterMessage()
    {
        $this->printSystemMessage(I18n::getInstance()->get('code.do-while-enter'));
    }

    protected function printExitMessage()
    {
        $this->printSystemMessage(I18n::getInstance()->get('code.do-while-exit'), $this->node->getAttribute('endLine'));
    }

    protected function printLoopCond()
    {
        $this->printMessage(
                I18n::getInstance()->get('code.loop-cond') . ' ' .
                $this->condition->message(), $this->node->getAttribute('endLine')
        );
    }

    protected function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        \PhpTestBed\ScriptCrawler::getInstance()->addLevel();
        if (!empty($this->node->stmts)) {
            do {
                $scriptCrawler->crawl($this->node->stmts);
                if ($scriptCrawler->getBreak()) {
                    break;
                }
                $this->condition = \PhpTestBed\Node\ResolverCondition::choose($this->node->cond);
                $this->printLoopCond();
            } while ($this->condition->getResult());
        }
        $scriptCrawler->removeLevel();
        $scriptCrawler->removeBreak();
    }

}
