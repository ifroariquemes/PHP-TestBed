<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class For_ extends \PhpTestBed\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\For_ $node)
    {
        parent::__construct($node);
    }

    private function testConditions()
    {
        foreach ($this->node->cond as $cond) {
            $binOp = new \PhpTestBed\Node\Expr\BinaryOp($cond);
            $this->printMessage(
                    I18n::getInstance()->get('code.if-cond') . ' ' .
                    $binOp->message()
            );
            if ($binOp->getResult() === false) {
                return false;
            }
        }
        return true;
    }

    protected function resolve()
    {
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.for-enter')
                )
        );
        \PhpTestBed\ScriptCrawler::getInstance()->addLevel();
        \PhpTestBed\ScriptCrawler::getInstance()->crawl($this->node->init);
        while ($this->testConditions()) {
            if (!empty($this->node->stmts)) {
                \PhpTestBed\ScriptCrawler::getInstance()->crawl($this->node->stmts);
            }
            if (!empty($this->node->loop)) {
                \PhpTestBed\ScriptCrawler::getInstance()->crawl($this->node->loop);
            }
        }
        \PhpTestBed\ScriptCrawler::getInstance()->removeLevel();
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.for-exit')
                )
                , $this->node->getAttribute('endLine')
        );
    }

}
