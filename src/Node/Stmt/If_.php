<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class If_ extends \PhpTestBed\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\If_ $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        $binOp = new \PhpTestBed\Node\Expr\BinaryOp($this->node->cond);
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.if-enter')
                )
        );
        \PhpTestBed\ScriptCrawler::getInstance()->addLevel();
        $this->printMessage(
                I18n::getInstance()->get('code.if-cond') . ' ' .
                $binOp->message()
        );
        $elseRun = true;
        if ($binOp->getResult()) {
            \PhpTestBed\ScriptCrawler::getInstance()->crawl($this->node->stmts);
            $elseRun = false;
        } elseif (!empty($this->node->elseifs) && $elseRun) {
            foreach ($this->node->elseifs as $elseif) {
                if ($elseRun) {
                    $elseIf = new ElseIf_($elseif);
                    $elseRun = !$elseIf->getResolveState();
                }
            }
        }
        if (!empty($this->node->else) && $elseRun) {
            $this->printMessage(
                    Stylizer::systemMessage(
                            I18n::getInstance()->get('code.else-cond')
                    )
                    , $this->node->else->getLine()
            );
            \PhpTestBed\ScriptCrawler::getInstance()
                    ->crawl($this->node->else->stmts);
        }
        \PhpTestBed\ScriptCrawler::getInstance()->removeLevel();
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.if-exit')
                        , $this->node->getAttribute('endLine')
                )
        );
    }

}
