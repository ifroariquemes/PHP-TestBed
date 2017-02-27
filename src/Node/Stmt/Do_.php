<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class Do_ extends \PhpTestBed\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\Do_ $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        $binOp = new \PhpTestBed\Node\Expr\BinaryOp($this->node->cond);
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.do-while-enter')
                )
        );
        \PhpTestBed\ScriptCrawler::getInstance()->addLevel();
        if (!empty($this->node->stmts)) {
            do {
                \PhpTestBed\ScriptCrawler::getInstance()->crawl($this->node->stmts);
                $binOp = new \PhpTestBed\Node\Expr\BinaryOp($this->node->cond);
                $this->printMessage(
                        I18n::getInstance()->get('code.loop-cond') . ' ' .
                        $binOp->message(), $this->node->getAttribute('endLine')
                );
            } while ($binOp->getResult());
        }
        \PhpTestBed\ScriptCrawler::getInstance()->removeLevel();
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.do-while-exit')
                )
                , $this->node->getAttribute('endLine')
        );
    }

}
