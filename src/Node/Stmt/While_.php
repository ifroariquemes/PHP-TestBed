<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class While_ extends \PhpTestBed\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\While_ $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.while-enter')
                )
        );
        \PhpTestBed\ScriptCrawler::getInstance()->addLevel();
        if (!empty($this->node->stmts)) {
            $binOp = new \PhpTestBed\Node\Expr\BinaryOp($this->node->cond);
            $this->printMessage(
                    I18n::getInstance()->get('code.loop-cond') . ' ' .
                    $binOp->message()
            );
            while ($binOp->getResult()) {
                \PhpTestBed\ScriptCrawler::getInstance()->crawl($this->node->stmts);
                $binOp = new \PhpTestBed\Node\Expr\BinaryOp($this->node->cond);
                $this->printMessage(
                        I18n::getInstance()->get('code.loop-cond') . ' ' .
                        $binOp->message()
                );
            }
        }
        \PhpTestBed\ScriptCrawler::getInstance()->removeLevel();
        $this->printMessage(
                Stylizer::systemMessage(
                        I18n::getInstance()->get('code.while-exit')
                )
                , $this->node->getAttribute('endLine')
        );
    }

}
