<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class If_ extends \PhpTestBed\ResolverAbstract
{

    private $condition;
    private $elseRun;

    public function __construct(\PhpParser\Node\Stmt\If_ $node)
    {
        $this->elseRun = true;
        parent::__construct($node);
    }

    protected function printEnterMessage()
    {
        $this->printSystemMessage(I18n::getInstance()->get('code.if-enter'));
    }

    protected function printExitMessage()
    {
        $this->printSystemMessage(I18n::getInstance()->get('code.if-exit'), $this->node->getAttribute('endLine'));
    }

    private function printIfCond()
    {
        $this->printMessage(
                I18n::getInstance()->get('code.if-cond') . ' ' .
                $this->condition->message()
        );
    }

    private function printElseCond()
    {
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.else-cond')
                )
                , $this->node->else->getLine()
        );
    }

    protected function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        switch (get_class($this->node->cond)) {
            case \PhpParser\Node\Expr\Variable::class:
                $this->condition = new \PhpTestBed\Node\Expr\Variable($this->node->cond);
                break;
            default:
                $this->condition = new \PhpTestBed\Node\Expr\BinaryOp($this->node->cond);
                break;
        }
        $scriptCrawler->addLevel();
        $this->printIfCond();
        $this->resolveIf();
        $this->resolveElse();
        $scriptCrawler->removeLevel();
    }

    private function resolveIf()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        if ($this->condition->getResult()) {
            $scriptCrawler->crawl($this->node->stmts);
            $this->elseRun = false;
        } elseif (!empty($this->node->elseifs) && $this->elseRun) {
            foreach ($this->node->elseifs as $elseif) {
                if ($this->elseRun) {
                    $elseIf = new ElseIf_($elseif);
                    $this->elseRun = !$elseIf->getResolveState();
                }
            }
        }
    }

    private function resolveElse()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        if (!empty($this->node->else) && $this->elseRun) {
            $this->printElseCond();
            $scriptCrawler->crawl($this->node->else->stmts);
        }
    }

}
