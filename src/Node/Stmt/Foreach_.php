<?php

namespace PhpTestBed\Node\Stmt;

use PhpTestBed\I18n;
use PhpTestBed\Stylizer;
use PhpTestBed\Node\Expr\Assign;

class Foreach_ extends \PhpTestBed\Node\ResolverAbstract
{

    public function __construct(\PhpParser\Node\Stmt\Foreach_ $node)
    {
        parent::__construct($node);
    }

    protected function printEnterMessage()
    {
        parent::__printEnterMessage('code.foreach-enter');
    }

    protected function printExitMessage()
    {
        parent::__printExitMessage('code.foreach-exit');
    }

    private function printNextItem($value)
    {
        if (is_array($value)) {
            
        }
        $mVar = [
            'var' => Stylizer::variable($this->node->valueVar->name),
            'origin' => Stylizer::variable($this->node->expr->name),
            'value' => (!is_array($value)) ? Stylizer::value($value) : Assign::prepareArrayToPrint($value)
        ];
        $this->printMessage(I18n::getInstance()->get('code.foreach-next-item', $mVar));
    }

    private function printNextKey($key)
    {
        $mVar = [
            'var' => Stylizer::variable($this->node->keyVar->name),
            'origin' => Stylizer::variable($this->node->expr->name),
            'value' => Stylizer::value($key)
        ];
        $this->printMessage(I18n::getInstance()->get('code.foreach-next-key', $mVar));
    }

    protected function resolve()
    {
        $scriptCrawler = \PhpTestBed\ScriptCrawler::getInstance();
        $scriptCrawler->addLevel();
        $expr = \PhpTestBed\Repository::getInstance()->get($this->node->expr->name);
        foreach ($expr as $key => $value) {
            if ($scriptCrawler->getBreak() || $scriptCrawler->getThrow()) {
                break;
            }
            if (!is_null($this->node->keyVar)) {
                \PhpTestBed\Repository::getInstance()->set($this->node->keyVar->name, $key);
                $this->printNextKey($key);
            }
            \PhpTestBed\Repository::getInstance()->set($this->node->valueVar->name, $value);
            $this->printNextItem($value);
            $scriptCrawler->crawl($this->node->stmts);
        }
        $scriptCrawler->removeLevel();
        $scriptCrawler->removeBreak();
    }

}
