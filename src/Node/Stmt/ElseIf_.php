<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;

class ElseIf_ extends \PhpTestBed\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\ElseIf_ $node)
    {
        parent::__construct($node);
    }

    protected function resolve()
    {
        $binOp = new \PhpTestBed\Node\Expr\BinaryOp($this->node->cond);
        $this->printMessage(
                I18n::getInstance()->get('code.if-cond') . ' ' .
                $binOp->message()
        );
        if ($binOp->getResult()) {
            \PhpTestBed\ScriptCrawler::getInstance()->crawl($this->node->stmts);
            $this->setResolveState(true);
        } else {
            $this->setResolveState(false);
        }
    }

}
