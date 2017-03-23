<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;

class While_ extends \PhpTestBed\ResolverAbstract
{

    private $condition;

    public function __construct(\PhpParser\Node\Stmt\While_ $node)
    {
        parent::__construct($node);
    }

    protected function printEnterMessage()
    {
        $this->printSystemMessage(I18n::getInstance()->get('code.while-enter'));
    }

    protected function printExitMessage()
    {
        $this->printSystemMessage(I18n::getInstance()->get('code.while-exit'), $this->node->getAttribute('endLine'));
    }

    private function printLoopCond()
    {
        $this->printMessage(
                I18n::getInstance()->get('code.loop-cond') . ' ' .
                $this->condition->message()
        );
    }

    protected function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        $scriptCrawler->addLevel();
        if (!empty($this->node->stmts)) {
            $this->condition = new \PhpTestBed\Node\Expr\BinaryOp($this->node->cond);
            $this->printLoopCond();
            while ($this->condition->getResult()) {
                $scriptCrawler->crawl($this->node->stmts);
                if($scriptCrawler->getBreak()) {
                    break;
                }
                $this->condition = new \PhpTestBed\Node\Expr\BinaryOp($this->node->cond);
                $this->printLoopCond();
            }
        }
        $scriptCrawler->removeLevel();
        $scriptCrawler->removeBreak();
    }

}
